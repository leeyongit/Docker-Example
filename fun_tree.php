<?php

/*
  对象版
  $items = array(
          (object) array('id' => 42, 'parent_id' => 1),
          (object) array('id' => 43, 'parent_id' => 42),
          (object) array('id' => 1,  'parent_id' => 0),
  );
*/
function buildTree($items) {

    $childs = array();

    foreach($items as $item)
        $childs[$item->parent_id][] = $item;

    foreach($items as $item) if (isset($childs[$item->id]))
        $item->childs = $childs[$item->id];

    return $childs[0];
}

$tree = buildTree($items);
/*
Output of print_r($tree):

stdClass Object
(
    [id] => 1
    [parent_id] => 0
    [childs] => Array
        (
            [0] => stdClass Object
                (
                    [id] => 42
                    [parent_id] => 1
                    [childs] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [id] => 43
                                    [parent_id] => 42
                                )

                        )

                )

        )

)
*/

// 数组版
function buildTree($items) {

    $childs = array();

    foreach($items as &$item) $childs[$item['parent_id']][] = &$item;
    unset($item);

    foreach($items as &$item) if (isset($childs[$item['id']]))
            $item['childs'] = $childs[$item['id']];

    return $childs[0];
}

$tree = buildTree($items);

// 递归版
function buildTree($items, $parent_id=0) {

	$tree = array();
	
	foreach ($items as $item) {
		if ($item['parent_id'] != $parent_id) continue;
		$tree[$item['id']] = $item;
		$childs = buildTree($items, $item['id']);
		if ($childs) $tree[$item['id']]['childs'] = $childs;
	}
	
	return $tree;
}

$tree = buildTree($items);
