<?php
/**
 * Plugin Name:  Bilişim SEO Oto Yazar
 * Plugin URI:   https://bilisim.k12.tr
 * Description:  Claude AI ile zamanlı SEO makalesi üretir, Yoast SEO ile entegre çalışır, otomatik görsel ekler.
 * Version:      1.1.0
 * Author:       Bilişim Koleji
 * Text Domain:  bilisim-seo-auto
 */

defined('ABSPATH') || exit;

define('BSEO_VER',  '1.3.0');
define('BSEO_FILE', __FILE__);

class BilisimSEOAuto {

    private static $inst = null;

    public static function get() {
        if (!self::$inst) self::$inst = new self();
        return self::$inst;
    }

    private function __construct() {
        add_action('admin_menu',            [$this, 'adminMenu']);
        add_action('bilisim_auto_post',     [$this, 'run']);
        add_action('wp_ajax_bseo_save',     [$this, 'ajaxSave']);
        add_action('wp_ajax_bseo_run_now',  [$this, 'ajaxRunNow']);
        add_action('wp_ajax_bseo_keywords', [$this, 'ajaxKeywords']);

        register_activation_hook(BSEO_FILE,   [$this, 'activate']);
        register_deactivation_hook(BSEO_FILE, [$this, 'deactivate']);
    }

    /* ─── Activate / Deactivate ──────────────────────────── */

    public function activate()   { $this->reschedule(); }
    public function deactivate() { wp_clear_scheduled_hook('bilisim_auto_post'); }

    /* ─── Settings ────────────────────────────────────────── */

    public function cfg() {
        return wp_parse_args(get_option('bseo_settings', []), [
            'claude_key'    => '',
            'unsplash_key'  => '',
            'pexels_key'    => '',
            'schedule_on'   => '0',
            'schedule_days' => ['monday', 'thursday'],
            'schedule_time' => '09:00',
            'post_status'   => 'publish',
            'post_category' => '',
            'niche'         => 'eğitim, özel okul, teknoloji, STEM',
            'keywords'      => '',
            'language'      => 'tr',
            'word_count'    => 600,
            'image_on'      => '1',
            'image_source'  => 'unsplash',
        ]);
    }

    /* ─── Scheduling ──────────────────────────────────────── */

    public function reschedule() {
        wp_clear_scheduled_hook('bilisim_auto_post');
        $c = $this->cfg();
        if ($c['schedule_on'] !== '1' || empty($c['schedule_days'])) return;
        $ts = $this->nextTs((array)$c['schedule_days'], $c['schedule_time']);
        if ($ts) wp_schedule_single_event($ts, 'bilisim_auto_post');
    }

    private function nextTs(array $days, string $time): ?int {
        $map = [
            'monday'=>1,'tuesday'=>2,'wednesday'=>3,'thursday'=>4,
            'friday'=>5,'saturday'=>6,'sunday'=>0
        ];
        [$h, $m] = array_map('intval', explode(':', $time));
        $now  = current_time('timestamp');
        $best = null;

        foreach ($days as $day) {
            if (!isset($map[$day])) continue;
            $tdow = $map[$day];
            $cdow = (int) date('w', $now);
            $diff = ($tdow - $cdow + 7) % 7;
            $ts   = strtotime(date('Y-m-d', $now)) + ($diff * DAY_IN_SECONDS) + ($h * HOUR_IN_SECONDS) + ($m * MINUTE_IN_SECONDS);
            if ($ts <= $now) $ts += 7 * DAY_IN_SECONDS;
            if ($best === null || $ts < $best) $best = $ts;
        }
        return $best;
    }

    /* ─── Main Runner ─────────────────────────────────────── */

    public function run() {
        $c = $this->cfg();
        if (empty($c['claude_key'])) {
            $this->log('❌ Claude API anahtarı eksik.');
            $this->reschedule();
            return;
        }

        $kw = $this->nextKeyword($c);
        $this->log("⏳ Makale üretiliyor → [{$kw}]");

        $art = $this->generate($kw, $c);
        if (!$art) {
            $this->log('❌ Makale üretilemedi.');
            $this->reschedule();
            return;
        }

        $img = null;
        if ($c['image_on'] === '1') {
            $query = $art['image_query'] ?? $kw;
            $img   = $this->fetchImage($query, $c);
            if (!$img) $this->log('⚠️ Görsel eklenemedi (API yanıtsız veya anahtar hatalı).');
        }

        $pid = $this->insertPost($art, $img, $kw, $c);
        if ($pid) $this->log("✅ Yayınlandı: #{$pid} — {$art['title']}");

        $this->reschedule();
    }

    /* ─── Keyword Rotation ────────────────────────────────── */

    private function nextKeyword(array $c): string {
        $raw   = trim($c['keywords']);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $raw))));
        /* Support comma-separated list on a single line */
        if (count($lines) === 1 && strpos($lines[0], ',') !== false) {
            $lines = array_values(array_filter(array_map('trim', explode(',', $lines[0]))));
        }
        if (empty($lines)) {
            /* Fallback: first item from niche, not the whole string */
            $parts = array_map('trim', explode(',', $c['niche']));
            return $parts[0] ?: $c['niche'];
        }
        $idx = (int) get_option('bseo_kw_idx', 0);
        $kw  = $lines[$idx % count($lines)];
        update_option('bseo_kw_idx', ($idx + 1) % count($lines));
        return $kw;
    }

    /* ─── Article Generation ──────────────────────────────── */

    private function generate(string $kw, array $c): ?array {
        $lang    = $c['language'] === 'tr' ? 'Türkçe' : 'İngilizce';
        $wc      = (int) $c['word_count'];
        $niche   = $c['niche'];
        $slug    = sanitize_title($kw);
        $siteUrl = rtrim(home_url(), '/');

        $prompt = <<<PROMPT
Sen bir {$lang} SEO içerik uzmanısın. "{$niche}" alanında faaliyet gösteren bir eğitim kurumu web sitesi için Yoast SEO uyumlu makale yazıyorsun.

ODAK ANAHTAR KELİME (tek): "{$kw}"
SİTE URL: {$siteUrl}

ZORUNLU YOAST SEO KURALLARI — tamamına uy, hiç atlama:

1. Makale başlığı (title) 50-60 karakter olsun ve odak anahtar kelimeyi BAŞINDA içersin.
2. İlk paragrafın ilk 100 kelimesinde odak anahtar kelimeyi kullan.
3. <h2> başlıklarından YALNIZCA BİRİ odak anahtar kelimeyi içersin; diğer tüm <h2>'ler farklı kelimelerle yazılsın. (Yoast: alt başlıkların %75'inden azı anahtar kelime içermeli.)
4. Odak anahtar kelime yazı genelinde TOPLAM EN FAZLA 10 KEZ geçsin (8-10 arası ideal).
5. Cümle uzunluğu ORTALAMA 15-18 kelime olsun. Uzun cümlelerden kaçın.
6. Her paragraf en fazla 3-4 cümle olsun (max ~100 kelime).
7. GEÇİŞ KELİMELERİ (kritik): Her 3 cümleden en az 1'i geçiş kelimesiyle başlamalı veya içermeli — toplam cümlelerin EN AZ %30'u geçiş kelimesi içersin. Kullanılacak kelimeler: öncelikle, ayrıca, bunun yanı sıra, öte yandan, bu nedenle, sonuç olarak, özellikle, bununla birlikte, dahası, örneğin, buna ek olarak, ancak, ne var ki, nitekim, kısacası, böylece, bu sayede, ilk olarak, ikinci olarak, son olarak, özetle.
8. EDİLGEN ÇATI (kritik): Tüm cümlelerin EN FAZLA %8'i edilgen çatı içersin. "-ılır, -ilir, -ılmış, -ilmiş, -ıldı, -ildi, -ılacak, -ilecek" eklerinden kaçın. "yapılır" yerine "yaparız", "sağlanır" yerine "sağlarız", "görülür" yerine "görürüz" kullan.
9. Yapı: giriş + en az 4 adet <h2> bölümü + sonuç paragrafı.
10. meta_description TAM OLARAK 120-155 karakter olsun, odak anahtar kelimeyi içersin.
11. DAHİLİ BAĞLANTILAR (zorunlu): İçeriğe en az 2 adet dahili bağlantı ekle. Şu sitedeki mantıklı sayfalara yönlendir: {$siteUrl}. Örnek linkler: {$siteUrl}/hakkimizda, {$siteUrl}/iletisim, {$siteUrl}/programlar, {$siteUrl}/kayit — bunları bağlam uygun yerlere metin içine yerleştir.
12. GİDEN BAĞLANTILAR (zorunlu): İçeriğe en az 2 adet harici bağlantı ekle. Güvenilir ve gerçek kaynaklara link ver; örneğin: meb.gov.tr, tubitak.gov.tr, tr.wikipedia.org veya konuyla ilgili gerçek Türk eğitim/bilim kaynakları. Her harici linke target="_blank" rel="noopener noreferrer" ekle.

Aşağıdaki geçerli JSON formatında YALNIZCA JSON yaz, başka hiçbir şey ekleme:

{
  "title": "50-60 karakter, odak anahtar kelime başta",
  "slug": "{$slug}",
  "content": "En az {$wc} kelime, HTML formatında. <h2> ve <h3> kullan. Kısa paragraflar. Dahili ve harici <a> bağlantıları içersin.",
  "meta_description": "120-155 karakter arası, odak anahtar kelime içersin",
  "tags": ["etiket1","etiket2","etiket3","etiket4","etiket5"],
  "excerpt": "2-3 kısa cümlelik özet",
  "image_query": "English search term for a relevant stock photo"
}
PROMPT;

        $res = $this->claude($c['claude_key'], $prompt, 4096);
        if (!$res) return null;

        if (preg_match('/\{[\s\S]*\}/m', $res, $m)) {
            $data = json_decode($m[0], true);
            if ($data && !empty($data['title']) && !empty($data['content'])) return $data;
        }

        $this->log('❌ JSON parse hatası. Yanıt: ' . substr($res, 0, 200));
        return null;
    }

    /* ─── Claude API ──────────────────────────────────────── */

    private function claude(string $key, string $prompt, int $max = 800): ?string {
        $r = wp_remote_post('https://api.anthropic.com/v1/messages', [
            'timeout' => 90,
            'headers' => [
                'x-api-key'         => $key,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ],
            'body' => json_encode([
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => $max,
                'messages'   => [['role' => 'user', 'content' => $prompt]],
            ]),
        ]);

        if (is_wp_error($r)) {
            $this->log('API WP_Error: ' . $r->get_error_message());
            return null;
        }

        $code = wp_remote_retrieve_response_code($r);
        $body = json_decode(wp_remote_retrieve_body($r), true);

        if ($code !== 200) {
            $this->log("API HTTP {$code}: " . ($body['error']['message'] ?? 'Bilinmeyen hata'));
            return null;
        }

        return $body['content'][0]['text'] ?? null;
    }

    /* ─── Image ───────────────────────────────────────────── */

    private function fetchImage(string $q, array $c): ?int {
        /* Seçilen kaynağı önce dene, sonra diğerine geç */
        if ($c['image_source'] === 'pexels') {
            if (!empty($c['pexels_key'])) {
                $id = $this->pexels($q, trim($c['pexels_key']));
                if ($id) return $id;
            }
            if (!empty($c['unsplash_key'])) return $this->unsplash($q, trim($c['unsplash_key']));
        } else {
            if (!empty($c['unsplash_key'])) {
                $id = $this->unsplash($q, trim($c['unsplash_key']));
                if ($id) return $id;
            }
            if (!empty($c['pexels_key'])) return $this->pexels($q, trim($c['pexels_key']));
        }
        return null;
    }

    private function unsplash(string $q, string $key): ?int {
        $r = wp_remote_get('https://api.unsplash.com/search/photos?' . http_build_query([
            'query'=>$q,'per_page'=>5,'orientation'=>'landscape'
        ]), ['timeout'=>20,'headers'=>['Authorization'=>'Client-ID '.$key]]);
        if (is_wp_error($r)) return null;
        $d = json_decode(wp_remote_retrieve_body($r), true);
        $url = $d['results'][0]['urls']['regular'] ?? null;
        $alt = $d['results'][0]['alt_description'] ?? $q;
        return $url ? $this->sideload($url, $alt) : null;
    }

    private function pexels(string $q, string $key): ?int {
        $r = wp_remote_get('https://api.pexels.com/v1/search?' . http_build_query([
            'query'=>$q,'per_page'=>5,'orientation'=>'landscape'
        ]), ['timeout'=>20,'headers'=>['Authorization'=>$key]]);

        if (is_wp_error($r)) {
            $this->log('Pexels WP_Error: ' . $r->get_error_message());
            return null;
        }
        $code = wp_remote_retrieve_response_code($r);
        if ($code !== 200) {
            $this->log("Pexels HTTP {$code} — API anahtarını kontrol edin.");
            return null;
        }
        $d   = json_decode(wp_remote_retrieve_body($r), true);
        $url = $d['photos'][0]['src']['large'] ?? null;
        $alt = $d['photos'][0]['alt'] ?? $q;
        if (!$url) { $this->log("Pexels: '{$q}' için sonuç bulunamadı."); return null; }
        return $this->sideload($url, $alt);
    }

    private function sideload(string $url, string $alt): ?int {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $tmp  = download_url($url);
        if (is_wp_error($tmp)) return null;
        $ext  = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $file = ['name' => sanitize_title($alt) . '.' . $ext, 'tmp_name' => $tmp];
        $id   = media_handle_sideload($file, 0, $alt);
        @unlink($tmp);
        return is_wp_error($id) ? null : $id;
    }

    /* ─── Post Insert ─────────────────────────────────────── */

    private function insertPost(array $a, ?int $img, string $kw, array $c): ?int {
        $cat = null;
        if (!empty($c['post_category'])) {
            $t = get_term_by('name', $c['post_category'], 'category');
            if ($t) {
                $cat = $t->term_id;
            } else {
                $n = wp_insert_term($c['post_category'], 'category');
                if (!is_wp_error($n)) $cat = $n['term_id'];
            }
        }

        /* Slug: anahtar kelimeden üret */
        $slug = sanitize_title($a['slug'] ?? $kw);

        /* Meta açıklama: 120-155 karakter zorunlu */
        $meta = trim($a['meta_description'] ?? '');
        if (mb_strlen($meta) > 155) {
            $meta = mb_substr($meta, 0, 152) . '...';
        }
        if (mb_strlen($meta) < 50) {
            /* Çok kısaysa excerpt'ten üret */
            $meta = mb_substr(wp_strip_all_tags($a['excerpt'] ?? $a['content']), 0, 152) . '...';
        }

        $args = [
            'post_title'   => wp_strip_all_tags($a['title']),
            'post_name'    => $slug,
            'post_content' => $a['content'],
            'post_excerpt' => $a['excerpt'] ?? '',
            'post_status'  => $c['post_status'],
            'post_author'  => 1,
            'post_type'    => 'post',
        ];
        if ($cat) $args['post_category'] = [$cat];

        $pid = wp_insert_post($args);
        if (is_wp_error($pid)) { $this->log('Post hatası: ' . $pid->get_error_message()); return null; }

        if (!empty($a['tags'])) wp_set_post_tags($pid, $a['tags'], false);
        if ($img)               set_post_thumbnail($pid, $img);

        /* Yoast SEO: odak kelime HER ZAMAN $kw, meta 120-155 char */
        update_post_meta($pid, '_yoast_wpseo_focuskw',  $kw);
        update_post_meta($pid, '_yoast_wpseo_metadesc', $meta);

        return $pid;
    }

    /* ─── Log ─────────────────────────────────────────────── */

    private function log(string $msg) {
        $logs   = get_option('bseo_logs', []);
        $logs[] = ['t' => current_time('mysql'), 'm' => $msg];
        if (count($logs) > 150) $logs = array_slice($logs, -150);
        update_option('bseo_logs', $logs);
    }

    /* ─── AJAX: Save ──────────────────────────────────────── */

    public function ajaxSave() {
        check_ajax_referer('bseo_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Yetkisiz');

        $days = [];
        if (!empty($_POST['schedule_days']) && is_array($_POST['schedule_days'])) {
            $days = array_map('sanitize_key', $_POST['schedule_days']);
        }

        $data = [
            'claude_key'    => sanitize_text_field($_POST['claude_key']    ?? ''),
            'unsplash_key'  => sanitize_text_field($_POST['unsplash_key']  ?? ''),
            'pexels_key'    => sanitize_text_field($_POST['pexels_key']    ?? ''),
            'schedule_on'   => isset($_POST['schedule_on']) && $_POST['schedule_on'] === '1' ? '1' : '0',
            'schedule_days' => $days,
            'schedule_time' => sanitize_text_field($_POST['schedule_time'] ?? '09:00'),
            'post_status'   => ($_POST['post_status'] ?? '') === 'draft' ? 'draft' : 'publish',
            'post_category' => sanitize_text_field($_POST['post_category'] ?? ''),
            'niche'         => sanitize_text_field($_POST['niche']         ?? ''),
            'keywords'      => sanitize_textarea_field($_POST['keywords']  ?? ''),
            'language'      => ($_POST['language'] ?? '') === 'en' ? 'en' : 'tr',
            'word_count'    => max(300, min(2000, (int)($_POST['word_count'] ?? 600))),
            'image_on'      => isset($_POST['image_on']) && $_POST['image_on'] === '1' ? '1' : '0',
            'image_source'  => ($_POST['image_source'] ?? '') === 'pexels' ? 'pexels' : 'unsplash',
        ];

        update_option('bseo_settings', $data);
        $this->reschedule();

        $next = wp_next_scheduled('bilisim_auto_post');
        wp_send_json_success([
            'msg'  => '✅ Ayarlar kaydedildi.',
            'next' => $next ? date_i18n('d.m.Y H:i', $next) : null,
        ]);
    }

    /* ─── AJAX: Run Now ───────────────────────────────────── */

    public function ajaxRunNow() {
        check_ajax_referer('bseo_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Yetkisiz');
        $this->run();
        $logs = array_reverse(get_option('bseo_logs', []));
        wp_send_json_success(['msg' => $logs[0]['m'] ?? 'Tamamlandı.']);
    }

    /* ─── AJAX: Keywords ──────────────────────────────────── */

    public function ajaxKeywords() {
        check_ajax_referer('bseo_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Yetkisiz');
        $c = $this->cfg();
        if (empty($c['claude_key'])) wp_send_json_error('Claude API anahtarı gerekli.');
        $niche  = sanitize_text_field($_POST['niche'] ?? $c['niche']);
        $prompt = "Türkiye'de '{$niche}' alanında arama yapılan 20 uzun kuyruklu (long-tail) SEO anahtar kelimesi listele. Her satıra tek bir anahtar kelime yaz, başka hiçbir şey ekleme.";
        $text   = $this->claude($c['claude_key'], $prompt, 600);
        if (!$text) wp_send_json_error('Claude yanıt vermedi.');
        wp_send_json_success(['keywords' => trim($text)]);
    }

    /* ─── Admin Menu ──────────────────────────────────────── */

    public function adminMenu() {
        add_menu_page('SEO Oto Yazar', 'SEO Oto Yazar', 'manage_options',
            'bseo', [$this, 'adminPage'], 'dashicons-edit-page', 25);
    }

    /* ─── Admin Page ──────────────────────────────────────── */

    public function adminPage() {
        $c    = $this->cfg();
        $next = wp_next_scheduled('bilisim_auto_post');
        $logs = array_reverse(get_option('bseo_logs', []));

        $days_tr = [
            'monday'=>'Pzt','tuesday'=>'Sal','wednesday'=>'Çar',
            'thursday'=>'Per','friday'=>'Cum','saturday'=>'Cmt','sunday'=>'Paz'
        ];

        $ajax_url = admin_url('admin-ajax.php');
        $nonce    = wp_create_nonce('bseo_nonce');
        ?>
        <style><?php echo $this->css(); ?></style>

        <div class="bseo-wrap">
          <div class="bseo-header">
            <h1>🤖 SEO Oto Yazar <span class="bseo-ver">v<?php echo BSEO_VER; ?></span></h1>
            <div id="bseo-next-box" class="bseo-next <?php echo $next ? '' : 'bseo-next-off'; ?>">
              <?php if ($next): ?>
                ⏰ Sonraki yayın: <strong><?php echo date_i18n('d.m.Y H:i', $next); ?></strong>
              <?php else: ?>
                ⏸ Zamanlama aktif değil
              <?php endif; ?>
            </div>
          </div>

          <div class="bseo-tabs" id="bseoTabs">
            <button class="bseo-tab active" data-t="api">🔑 API</button>
            <button class="bseo-tab" data-t="schedule">📅 Program</button>
            <button class="bseo-tab" data-t="content">✍️ İçerik</button>
            <button class="bseo-tab" data-t="image">🖼️ Görsel</button>
            <button class="bseo-tab" data-t="log">📋 Geçmiş (<?php echo count($logs); ?>)</button>
          </div>

          <!-- API -->
          <div class="bseo-panel active" id="bseo-api">
            <div class="bseo-card">
              <h3>Claude API (Anthropic)</h3>
              <p class="bseo-hint">→ <a href="https://console.anthropic.com" target="_blank">console.anthropic.com</a> adresinden API anahtarı alın.</p>
              <label>Claude API Anahtarı</label>
              <input type="password" id="f_claude_key" value="<?php echo esc_attr($c['claude_key']); ?>" placeholder="sk-ant-..." class="bseo-input">
            </div>
            <div class="bseo-card">
              <h3>Görsel API (en az birini doldurun)</h3>
              <div class="bseo-g2">
                <div>
                  <label>Unsplash API Key <a href="https://unsplash.com/developers" target="_blank">(ücretsiz)</a></label>
                  <input type="password" id="f_unsplash_key" value="<?php echo esc_attr($c['unsplash_key']); ?>" placeholder="..." class="bseo-input">
                </div>
                <div>
                  <label>Pexels API Key <a href="https://www.pexels.com/api/" target="_blank">(ücretsiz)</a></label>
                  <input type="password" id="f_pexels_key" value="<?php echo esc_attr($c['pexels_key']); ?>" placeholder="..." class="bseo-input">
                </div>
              </div>
            </div>
          </div>

          <!-- Schedule -->
          <div class="bseo-panel" id="bseo-schedule">
            <div class="bseo-card">
              <h3>Yayın Programı</h3>
              <label class="bseo-switch">
                <input type="checkbox" id="f_schedule_on" value="1" <?php checked($c['schedule_on'], '1'); ?>>
                <span class="bseo-slider"></span>
                <strong>Otomatik yayını aktif et</strong>
              </label>

              <div style="margin-top:1.5rem;">
                <label>Yayın Günleri</label>
                <div class="bseo-days">
                  <?php foreach ($days_tr as $key => $label):
                    $checked = in_array($key, (array)$c['schedule_days']); ?>
                  <label class="bseo-day <?php echo $checked ? 'on' : ''; ?>">
                    <input type="checkbox" class="day-cb" value="<?php echo $key; ?>" <?php checked($checked); ?>>
                    <?php echo $label; ?>
                  </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="bseo-g2" style="margin-top:1rem;">
                <div>
                  <label>Saat</label>
                  <input type="time" id="f_schedule_time" value="<?php echo esc_attr($c['schedule_time']); ?>" class="bseo-input-sm">
                </div>
                <div>
                  <label>Yayın Durumu</label>
                  <select id="f_post_status" class="bseo-select">
                    <option value="publish" <?php selected($c['post_status'],'publish'); ?>>Yayınla</option>
                    <option value="draft"   <?php selected($c['post_status'],'draft'); ?>>Taslak</option>
                  </select>
                </div>
              </div>

              <div style="margin-top:1rem;">
                <label>Kategori <small>(boş bırakılabilir — yoksa otomatik oluşturulur)</small></label>
                <input type="text" id="f_post_category" value="<?php echo esc_attr($c['post_category']); ?>" placeholder="Eğitim Haberleri" class="bseo-input">
              </div>
            </div>
          </div>

          <!-- Content -->
          <div class="bseo-panel" id="bseo-content">
            <div class="bseo-card">
              <h3>Site Bilgileri</h3>
              <div class="bseo-g2">
                <div>
                  <label>Site Nişi / Konusu</label>
                  <input type="text" id="f_niche" value="<?php echo esc_attr($c['niche']); ?>" placeholder="eğitim, okul, STEM" class="bseo-input">
                </div>
                <div>
                  <label>Dil</label>
                  <select id="f_language" class="bseo-select">
                    <option value="tr" <?php selected($c['language'],'tr'); ?>>Türkçe</option>
                    <option value="en" <?php selected($c['language'],'en'); ?>>İngilizce</option>
                  </select>
                </div>
              </div>
              <div style="margin-top:1rem;">
                <label>Minimum Kelime Sayısı</label>
                <input type="number" id="f_word_count" value="<?php echo esc_attr($c['word_count']); ?>" min="300" max="2000" step="100" class="bseo-input-sm">
              </div>
            </div>

            <div class="bseo-card">
              <h3>Anahtar Kelimeler</h3>
              <p class="bseo-hint">Her satıra bir anahtar kelime. Sırayla kullanılır ve döngüsel tekrar eder.</p>
              <textarea id="f_keywords" rows="10" class="bseo-textarea" placeholder="özel okul ankara&#10;bilişim eğitimi&#10;STEM eğitimi nedir&#10;yapay zeka ve eğitim&#10;LGS hazırlık kursu ankara"><?php echo esc_textarea($c['keywords']); ?></textarea>
              <button type="button" id="bseo-kw-btn" class="bseo-btn bseo-outline" style="margin-top:.75rem;">🔍 Claude ile Otomatik Kelime Öner</button>
              <div id="bseo-kw-result" style="display:none;margin-top:.75rem;"></div>
            </div>
          </div>

          <!-- Image -->
          <div class="bseo-panel" id="bseo-image">
            <div class="bseo-card">
              <h3>Otomatik Görsel</h3>
              <label class="bseo-switch">
                <input type="checkbox" id="f_image_on" value="1" <?php checked($c['image_on'], '1'); ?>>
                <span class="bseo-slider"></span>
                <strong>Makaleye otomatik öne çıkan görsel ekle</strong>
              </label>
              <div style="margin-top:1.5rem;">
                <label>Görsel Kaynağı</label>
                <select id="f_image_source" class="bseo-select">
                  <option value="unsplash" <?php selected($c['image_source'],'unsplash'); ?>>Unsplash</option>
                  <option value="pexels"   <?php selected($c['image_source'],'pexels'); ?>>Pexels</option>
                </select>
              </div>
              <p class="bseo-hint" style="margin-top:.75rem;">Görsel arama terimi makale konusuna göre Claude tarafından otomatik belirlenir.</p>
            </div>
          </div>

          <!-- Log -->
          <div class="bseo-panel" id="bseo-log">
            <div class="bseo-card">
              <h3>İşlem Geçmişi</h3>
              <?php if (empty($logs)): ?>
                <p class="bseo-hint">Henüz kayıt yok.</p>
              <?php else: ?>
                <div class="bseo-log">
                  <?php foreach ($logs as $l): ?>
                  <div class="bseo-log-row">
                    <span class="bseo-log-t"><?php echo esc_html($l['t']); ?></span>
                    <span class="bseo-log-m"><?php echo esc_html($l['m']); ?></span>
                  </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div class="bseo-bar">
            <button id="bseo-save" class="bseo-btn bseo-primary">💾 Ayarları Kaydet</button>
            <button id="bseo-run"  class="bseo-btn bseo-success">▶ Şimdi Makale Oluştur</button>
          </div>

          <div id="bseo-msg" class="bseo-msg" style="display:none;"></div>
        </div>

        <script>
        (function($){
          var AJAX  = '<?php echo esc_js($ajax_url); ?>';
          var NONCE = '<?php echo esc_js($nonce); ?>';

          /* Tabs */
          $('#bseoTabs').on('click','.bseo-tab',function(){
            var t=$(this).data('t');
            $('.bseo-tab').removeClass('active');
            $(this).addClass('active');
            $('.bseo-panel').removeClass('active');
            $('#bseo-'+t).addClass('active');
          });

          /* Day toggle */
          $(document).on('change','.day-cb',function(){
            $(this).closest('.bseo-day').toggleClass('on',this.checked);
          });

          /* Collect */
          function collect(){
            var days=[];
            $('.day-cb:checked').each(function(){ days.push(this.value); });
            return {
              claude_key:    $('#f_claude_key').val(),
              unsplash_key:  $('#f_unsplash_key').val(),
              pexels_key:    $('#f_pexels_key').val(),
              schedule_on:   $('#f_schedule_on').is(':checked') ? '1' : '0',
              'schedule_days[]': days,
              schedule_time: $('#f_schedule_time').val(),
              post_status:   $('#f_post_status').val(),
              post_category: $('#f_post_category').val(),
              niche:         $('#f_niche').val(),
              keywords:      $('#f_keywords').val(),
              language:      $('#f_language').val(),
              word_count:    $('#f_word_count').val(),
              image_on:      $('#f_image_on').is(':checked') ? '1' : '0',
              image_source:  $('#f_image_source').val(),
            };
          }

          /* Message */
          function msg(txt, ok){
            var el=$('#bseo-msg');
            el.attr('class','bseo-msg '+(ok?'bseo-ok':'bseo-err')).html(txt).show();
            if(ok) setTimeout(function(){ el.fadeOut(); },4500);
          }

          /* Save */
          $('#bseo-save').on('click',function(){
            var btn=$(this).prop('disabled',true).text('Kaydediliyor…');
            $.post(AJAX, $.extend({action:'bseo_save',nonce:NONCE}, collect()))
              .done(function(r){
                if(r.success){
                  var nextTxt = r.data.next ? ' | Sonraki: <strong>'+r.data.next+'</strong>' : '';
                  msg(r.data.msg + nextTxt, true);
                  /* Update header */
                  if(r.data.next){
                    $('#bseo-next-box').removeClass('bseo-next-off').html('⏰ Sonraki yayın: <strong>'+r.data.next+'</strong>');
                  } else {
                    $('#bseo-next-box').addClass('bseo-next-off').html('⏸ Zamanlama aktif değil');
                  }
                } else {
                  msg('❌ '+(r.data||'Bilinmeyen hata'),false);
                }
              })
              .fail(function(xhr){ msg('❌ AJAX hatası: '+xhr.status,false); })
              .always(function(){ btn.prop('disabled',false).text('💾 Ayarları Kaydet'); });
          });

          /* Run Now — önce formu kaydeder, sonra makale oluşturur */
          $('#bseo-run').on('click',function(){
            if(!confirm('Şimdi makale oluşturulsun mu? Bu işlem 30-60 saniye sürebilir.')) return;
            var btn=$(this).prop('disabled',true).text('⏳ Kaydediliyor…');
            $.post(AJAX, $.extend({action:'bseo_save',nonce:NONCE}, collect()))
              .done(function(){
                btn.text('⏳ Makale oluşturuluyor…');
                $.post(AJAX,{action:'bseo_run_now',nonce:NONCE})
                  .done(function(r){
                    if(r.success){
                      msg('✅ '+r.data.msg,true);
                      setTimeout(function(){ location.reload(); },3000);
                    } else {
                      msg('❌ '+(r.data||'Hata'),false);
                    }
                  })
                  .fail(function(xhr){ msg('❌ AJAX hatası: '+xhr.status,false); })
                  .always(function(){ btn.prop('disabled',false).text('▶ Şimdi Makale Oluştur'); });
              })
              .fail(function(){ btn.prop('disabled',false).text('▶ Şimdi Makale Oluştur'); msg('❌ Ayarlar kaydedilemedi.',false); });
          });

          /* Keyword suggest */
          $('#bseo-kw-btn').on('click',function(){
            var btn=$(this).prop('disabled',true).text('⏳ Öneriler alınıyor…');
            $.post(AJAX,{action:'bseo_keywords',nonce:NONCE,niche:$('#f_niche').val()})
              .done(function(r){
                if(r.success){
                  var res=$('#bseo-kw-result');
                  res.html('<div class="bseo-kw-box"><strong>Önerilen Kelimeler:</strong><pre>'+r.data.keywords+'</pre><button type="button" id="bseo-kw-add" class="bseo-btn bseo-outline" style="font-size:.78rem;padding:.35rem .8rem;">+ Listeye Ekle</button></div>').show();
                  res.find('#bseo-kw-add').on('click',function(){
                    var cur=$('#f_keywords').val().trim();
                    $('#f_keywords').val((cur?cur+'\n':'')+r.data.keywords);
                    res.hide();
                  });
                } else {
                  msg('❌ '+(r.data||'Hata'),false);
                }
              })
              .fail(function(){ msg('❌ Bağlantı hatası.',false); })
              .always(function(){ btn.prop('disabled',false).text('🔍 Claude ile Otomatik Kelime Öner'); });
          });
        })(jQuery);
        </script>
        <?php
    }

    /* ─── CSS ─────────────────────────────────────────────── */

    private function css(): string { return '
.bseo-wrap{max-width:960px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
.bseo-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.5rem}
.bseo-header h1{margin:0;font-size:1.5rem}
.bseo-ver{font-size:.7rem;background:#e0e7ff;color:#3730a3;padding:.2rem .55rem;border-radius:30px;font-weight:600;vertical-align:middle;margin-left:.4rem}
.bseo-next{font-size:.85rem;background:#f0fdf4;color:#166534;padding:.5rem 1rem;border-radius:8px;border:1px solid #bbf7d0}
.bseo-next-off{background:#fef9c3!important;color:#854d0e!important;border-color:#fde68a!important}
.bseo-tabs{display:flex;gap:.25rem;border-bottom:2px solid #e5e7eb;margin-bottom:1.5rem;flex-wrap:wrap}
.bseo-tab{padding:.6rem 1.1rem;border:none;background:none;font-size:.85rem;font-weight:600;color:#6b7280;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;border-radius:6px 6px 0 0;transition:all .2s}
.bseo-tab:hover{color:#1d4ed8;background:#eff6ff}
.bseo-tab.active{color:#1d4ed8;border-bottom-color:#1d4ed8;background:#eff6ff}
.bseo-panel{display:none}
.bseo-panel.active{display:block}
.bseo-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem;margin-bottom:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.05)}
.bseo-card h3{margin:0 0 1rem;font-size:1rem;color:#111827}
.bseo-hint{font-size:.82rem;color:#6b7280;margin:0 0 .75rem;line-height:1.5}
.bseo-hint a{color:#1d4ed8}
label{display:block;font-size:.82rem;font-weight:600;color:#374151;margin-bottom:.35rem}
.bseo-input{width:100%;padding:.55rem .8rem;border:1px solid #d1d5db;border-radius:8px;font-size:.88rem;outline:none;transition:border .2s;box-sizing:border-box}
.bseo-input:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
.bseo-input-sm{width:180px;padding:.5rem .75rem;border:1px solid #d1d5db;border-radius:8px;font-size:.88rem;outline:none}
.bseo-select{padding:.55rem .75rem;border:1px solid #d1d5db;border-radius:8px;font-size:.88rem;outline:none;background:#fff;cursor:pointer}
.bseo-textarea{width:100%;padding:.65rem .85rem;border:1px solid #d1d5db;border-radius:8px;font-size:.84rem;resize:vertical;outline:none;box-sizing:border-box;font-family:monospace;line-height:1.6}
.bseo-textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
.bseo-g2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.bseo-switch{display:flex;align-items:center;gap:.75rem;cursor:pointer;user-select:none}
.bseo-switch input{display:none}
.bseo-slider{position:relative;width:44px;height:24px;background:#d1d5db;border-radius:30px;transition:background .25s;flex-shrink:0}
.bseo-slider::after{content:"";position:absolute;width:18px;height:18px;background:#fff;border-radius:50%;top:3px;left:3px;transition:transform .25s;box-shadow:0 1px 3px rgba(0,0,0,.2)}
.bseo-switch input:checked+.bseo-slider{background:#2563eb}
.bseo-switch input:checked+.bseo-slider::after{transform:translateX(20px)}
.bseo-days{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.4rem}
.bseo-day{padding:.45rem .9rem;border:1.5px solid #d1d5db;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;transition:all .2s;color:#374151;user-select:none}
.bseo-day.on{background:#eff6ff;border-color:#2563eb;color:#1d4ed8}
.bseo-day input{display:none}
.bseo-btn{padding:.65rem 1.4rem;border-radius:8px;font-size:.88rem;font-weight:600;cursor:pointer;border:none;transition:all .2s}
.bseo-primary{background:#2563eb;color:#fff}
.bseo-primary:hover{background:#1d4ed8;transform:translateY(-1px);box-shadow:0 4px 12px rgba(37,99,235,.3)}
.bseo-success{background:#059669;color:#fff}
.bseo-success:hover{background:#047857;transform:translateY(-1px);box-shadow:0 4px 12px rgba(5,150,105,.3)}
.bseo-outline{background:#fff;color:#374151;border:1.5px solid #d1d5db}
.bseo-outline:hover{border-color:#2563eb;color:#1d4ed8;background:#eff6ff}
.bseo-btn:disabled{opacity:.5;cursor:not-allowed;transform:none!important;box-shadow:none!important}
.bseo-bar{display:flex;gap:.75rem;margin-top:.5rem;padding-top:1.25rem;border-top:1px solid #e5e7eb}
.bseo-msg{margin-top:1rem;padding:.75rem 1.1rem;border-radius:8px;font-size:.88rem;font-weight:500}
.bseo-ok{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0}
.bseo-err{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.bseo-log{border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;max-height:500px;overflow-y:auto}
.bseo-log-row{display:flex;gap:1rem;padding:.6rem 1rem;border-bottom:1px solid #f3f4f6;font-size:.8rem}
.bseo-log-row:last-child{border-bottom:none}
.bseo-log-row:nth-child(even){background:#f9fafb}
.bseo-log-t{color:#9ca3af;white-space:nowrap;flex-shrink:0}
.bseo-log-m{color:#111827}
.bseo-kw-box{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:1rem}
.bseo-kw-box pre{font-size:.82rem;line-height:1.7;margin:.5rem 0 .75rem;white-space:pre-wrap;font-family:monospace}
@media(max-width:640px){.bseo-g2{grid-template-columns:1fr}}
    ';}
}

BilisimSEOAuto::get();
