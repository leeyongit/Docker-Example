<?php
class cateTree {
	private $rows;
	private $tree = array();

	public function __construct($rows) {
		$this->rows = $rows;
	}

	private function findChild(&$arr, $pid) {
		$childs = array();
		foreach ($arr as $k => $v) {
			if ($v['parent_id'] == $pid) {
				$childs[] = $v;
			}
		}

		return $childs;
	}

	public function buildTree($pid) {
		$childs = $this->findChild($this->rows, $pid);
		if (empty($childs)) {
			return null;
		}

		foreach ($childs as $k => $v) {
			$resTree = $this->buildTree($v['cat_id']);
			if ($resTree != null) {
				$childs[$k]['childs'] = $resTree;
			}
		}

		return $childs;
	}

	public function getTree($pid=0,$lv=0){
		$lv++;
   		foreach ($this->rows as $row){
	       	if ($row["parent_id"]==$pid){
	       		$row["lv"]=$lv;
	       		$row['tab'] = str_repeat('|------', $lv-1);
	       		$this->tree[]=$row;
	       		$this->getTree($row["cat_id"],$lv);
	       	}
   		}
   		

   		return $this->tree;
	}
}


?>