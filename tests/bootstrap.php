<?php
/**
 * Spiral Framework, SpiralScout LLC.
 *
 * @author    Anton Titov (Wolfy-J)
 */

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

//Composer
require dirname(__DIR__) . '/vendor/autoload.php';


$c = [
    'a'      => ['a', 'b', 'd'],
    'b'      => [1, 2, 3],
    'auth'   => ['on', 'off'],
    'locale' => ['en', 'ru', 'de', 'cn', 'zn', 'ja', 'la'],
];

print_r(gen($c));

function gen(array $options): array
{
    $key = array_keys($options)[0];
    $values = $options[$key];

    unset($options[$key]);

    $result = [];
    foreach ($values as $value) {
        $item = [$key => $value];

        if (!empty($options)) {
            foreach (gen($options) as $nested) {
                $result[] = $item + $nested;
            }
        } else {
            $result[] = $item;
        }
    }

    return $result;
}