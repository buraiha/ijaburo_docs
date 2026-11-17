<?php
// Isolated rendering tests. WordPress APIs are stubbed; live integration is checked separately.
class WP_Error {}
const GW_TAXONOMY_NAME_REPORT = 'report_category';
function get_the_ID() { return 42; }
function get_the_term_list($id, $taxonomy, $before, $separator, $after) {
    global $test_result;
    if (!is_array($test_result)) return $test_result;
    return $before . implode($separator, $test_result) . $after;
}
function is_wp_error($value) { return $value instanceof WP_Error; }
function wp_kses_post($value) { return $value; }
$templates = [
    'single' => base64_decode('PD9waHAgJHJlcG9ydF9jYXRlZ29yeV9saW5rcyA9IGdldF90aGVfdGVybV9saXN0KGdldF90aGVfSUQoKSwgR1dfVEFYT05PTVlfTkFNRV9SRVBPUlQsICc8c3BhbiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrOyBib3JkZXI6IDFweCBzb2xpZCAjODI4MjgyOyBib3JkZXItcmFkaXVzOiA2cHg7IHBhZGRpbmc6IDZweCAxMHB4OyI+JywgJzwvc3Bhbj48c3BhbiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrOyBib3JkZXI6IDFweCBzb2xpZCAjODI4MjgyOyBib3JkZXItcmFkaXVzOiA2cHg7IHBhZGRpbmc6IDZweCAxMHB4OyI+JywgJzwvc3Bhbj4nKTsgPz4KICAgICAgICAgICAgICAgICAgICA8P3BocCBpZiAoJHJlcG9ydF9jYXRlZ29yeV9saW5rcyAmJiAhaXNfd3BfZXJyb3IoJHJlcG9ydF9jYXRlZ29yeV9saW5rcykpOiA/PgogICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Imd3LXJlcG9ydF9faGVhZC1tZXRhLWNhdGVnb3J5IiBzdHlsZT0iZGlzcGxheTogZmxleDsgZmxleC13cmFwOiB3cmFwOyBnYXA6IDhweDsgYm9yZGVyOiAwOyBwYWRkaW5nOiAwOyI+PD9waHAgZWNobyB3cF9rc2VzX3Bvc3QoJHJlcG9ydF9jYXRlZ29yeV9saW5rcyk7ID8+PC9kaXY+CiAgICAgICAgICAgICAgICAgICAgPD9waHAgZW5kaWY7ID8+'),
    'archive' => base64_decode('PD9waHAgJHJlcG9ydF9jYXRlZ29yeV9saW5rcyA9IGdldF90aGVfdGVybV9saXN0KGdldF90aGVfSUQoKSwgR1dfVEFYT05PTVlfTkFNRV9SRVBPUlQsICc8c3BhbiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrOyBib3JkZXI6IDFweCBzb2xpZCAjODI4MjgyOyBib3JkZXItcmFkaXVzOiA2cHg7IHBhZGRpbmc6IDZweCAxMHB4OyI+JywgJzwvc3Bhbj48c3BhbiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrOyBib3JkZXI6IDFweCBzb2xpZCAjODI4MjgyOyBib3JkZXItcmFkaXVzOiA2cHg7IHBhZGRpbmc6IDZweCAxMHB4OyI+JywgJzwvc3Bhbj4nKTsgPz4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8P3BocCBpZiAoJHJlcG9ydF9jYXRlZ29yeV9saW5rcyAmJiAhaXNfd3BfZXJyb3IoJHJlcG9ydF9jYXRlZ29yeV9saW5rcykpOiA/PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkZCBjbGFzcz0iZ3ctcmVwb3J0X19hcmNoaXZlLWl0ZW0tY2F0ZWdvcnkiIHN0eWxlPSJkaXNwbGF5OiBmbGV4OyBmbGV4LXdyYXA6IHdyYXA7IGdhcDogOHB4OyBib3JkZXI6IDA7IHBhZGRpbmc6IDA7Ij48P3BocCBlY2hvIHdwX2tzZXNfcG9zdCgkcmVwb3J0X2NhdGVnb3J5X2xpbmtzKTsgPz48L2RkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/cGhwIGVuZGlmOyA/Pg=='),
 ];
$cases = ['none' => false, 'error' => new WP_Error(), 'one' => ['<a href="/one/" rel="tag">one</a>'], 'multiple' => ['<a href="/one/" rel="tag">one</a>', '<a href="/two/" rel="tag">two</a>', '<a href="/three/" rel="tag">three</a>']];
$count = 0;
foreach ($templates as $name => $code) {
    foreach ($cases as $case => $value) {
        $test_result = $value;
        ob_start(); eval('?>' . $code); $html = trim(ob_get_clean());
        $expected = is_array($value) ? count($value) : 0;
        if (($expected === 0 && $html !== '') || substr_count($html, '<a ') !== $expected || substr_count($html, '<span ') !== $expected) {
            fwrite(STDERR, "FAIL $name/$case\n"); exit(1);
        }
        if ($expected > 0 && (!str_contains($html, 'flex-wrap: wrap') || !str_contains($html, 'rel="tag"'))) {
            fwrite(STDERR, "FAIL wrapping/link $name/$case\n"); exit(1);
        }
        echo "PASS $name/$case\n";
        $count++;
    }
}
echo "PASS $count isolated rendering cases\n";
