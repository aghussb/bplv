<?php
namespace App\Helpers;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Helper
{
    public static function mergedMenus(array $arrays)
    {
        $uniqueArray = [];

        foreach ($arrays as $item) {
            $id = $item["id"];

            if (!isset($uniqueArray[$id])) {
                $uniqueArray[$id] = $item;
            }
        }

        $uniqueArray = array_values($uniqueArray);

        // Mengurutkan array berdasarkan "urutan"
        usort($uniqueArray, function ($a, $b) {
            return $a['urutan'] - $b['urutan'];
        });
        return $uniqueArray;
    }

    public static function buildTree(array $array, $status, $parentId = 0)
    {
        $tree = [];

        foreach ($array as $item) {
            if (!empty($item['route_name'])) $item['url'] = route($item['route_name']);

            if ($item['parent_id'] == $parentId) {

                if ($status) {
                    $item['items'] = [];
                }

                $children = (new Helper())->buildTree($array, $status, $item['id']);
                if (!empty($children)) {
                    $item['items'] = $children;
                }

                $tree[] = $item;
            }
        }

        return $tree;
    }

    public static function searchNestedArray($array, $key, $value)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $subArray) {
            if (isset($subArray[$key]) && $subArray[$key] === $value) {
                return $subArray;
            }
        }

        return false;
    }
}
