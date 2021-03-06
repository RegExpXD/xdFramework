<?php
//表关系映射，增删改查方法
class core_store{

	private $tableName;

	private $databaseName;

	private $queryStr;

	private $fieldStr;

	private $joinStr;

	private $whereStr;

	private $groupStr;

	private $havingStr;

	private $orderStr;

	private $limitStr;

	public function __construct($tableName = null,$databaseName = DEFAULT_DATABASE){
		$class = get_called_class();
		if(!empty($tableName) && !empty($databaseName)){
			$this->tableName = $tableName;
			$this->databaseName = $databaseName;
		}else{
			$this->tableName = substr($class, strpos($class,'_')+1,strrpos($class,'_') - strpos($class, '_') - 1);
			$this->databaseName = substr($class, 0 ,strpos($class,'_'));
		}
	}

	public function field($fieldStr){
		$this->fieldStr = $fieldStr;
		return $this;
	}

	public function join($joinStr){
		$this->joinStr = $joinStr;
		return $this;
	}

	public function where($args){
		$str = ' WHERE ';
		if(is_array($args)){
			foreach($args as $k => $v){
			    if(strtoupper($k) == '_LOGISTIC_'){
                    $str .= $v;
                }else if(is_array($v)){
			        $logic = isset($v['_logistic_'])?$v['_logistic_']:' AND ';
					switch (strtoupper(current($v))) {
						case 'IN':
							$str .= '`'.$k.'` IN ("'.implode($v[1],'","').'")';
							break;
						case 'GT':
							$str .= '`'.$k.'` > '.intval($v[1]).'';
							break;
						case 'LT':
							$str .= '`'.$k.'` < '.intval($v[1]).'';
							break;
						case 'EQ':
							$str .= '`'.$k.'` = '.intval($v[1]).'';
							break;
						case 'ELT':
							$str .= '`'.$k.'` <= '.intval($v[1]).'';
							break;
						case 'EGT':
							$str .= '`'.$k.'` >= '.intval($v[1]).'';
							break;
						case 'BETWEEN':
							$str .= '`'.$k.'` BETWEEN '.$v[1][0].' AND '.$v[1][1];
							break;
                        case 'LIKE':
                            $str .= '`'.$k.'` LIKE "'.$v[1].'"';
                            break;
                        case 'NOT LIKE':
                            $str .= '`'.$k.'` NOT LIKE "'.$v[1].'"';
                            break;
					}
					$str .=  $logic;
				}else{
					$str .= ' `'.$k.'` = '.$v;
				}
			}
            //去除尾部逻辑连接符号
            $str = preg_replace('/(and|or|xor)\s*$/i','',$str);
		}else{
			$str = $args;
		}
		$this->whereStr = $str;
		return $this;
	}

	public function group($groupStr){
		$this->groupStr = ' GROUP BY '.$groupStr;
		return $this;
	}

	public function having($havingStr){
		$this->havingStr = ' HAVING '.$havingStr;
		return $this;
	}

	public function order($orderStr){
		$this->orderStr = ' ORDER BY '.$orderStr;
		return $this;
	}

	public function limit($limitStr){
		$this->limitStr = ' LIMIT '.$limitStr;
		return $this;
	}

	private function getQueryStr($type,$data = null){
		$queryStr = '';
		if($type == 'select'){
			$queryStr = 'SELECT '.($this->fieldStr?$this->fieldStr:'*').' FROM '.($this->joinStr?$this->joinStr:"`{$this->tableName}`").$this->whereStr;
			!empty($this->groupStr) && $queryStr .= $this->groupStr;
			!empty($this->havingStr) && $queryStr .= $this->havingStr;
			!empty($this->orderStr) && $queryStr .= $this->orderStr;
			!empty($this->limitStr) && $queryStr .= $this->limitStr;
		}else if($type == 'update'){
			$queryStr = 'UPDATE '.($this->joinStr?$this->joinStr:$this->tableName).' SET ';
			foreach($data as $k => $v){
				$queryStr .= '`'.$k.'` = "'.$v.'" AND ';
			}
			$queryStr = rtrim($queryStr,' AND ');
			if(empty($this->whereStr)){
				throw new Exception('update operate need where,but it is empty now!');
			}
			$queryStr .=  $this->whereStr;//这里需要禁用全表更新
		}else if($type == 'save'){
		    $firstElement = current($data);
		    if(is_array($firstElement)){//二维数组，插入多条
                $queryStr = 'INSERT INTO '.$this->tableName.' (`';
                $fields = array_keys($firstElement);
                $queryStr .= implode('`,`',$fields).'`) VALUES';

                foreach($data as $k => $v){
                    $queryStr .= '("'.implode('","',array_values($v)).'"),';
                }
                $queryStr = rtrim($queryStr,',');
            }else{
                $queryStr = 'INSERT INTO '.$this->tableName.' SET ';
		        foreach($data as $k => $v){
		            $queryStr .= '`'.$k.'` = '.$v.',';
                }
                $queryStr = rtrim($queryStr,',');
            }
		}else if($type == 'delete'){
			if(empty($this->whereStr)){
				throw new Exception('delete operate need where,but it is empty now!');
			}
			$queryStr .=  $this->whereStr;//这里需要禁用全表更新
		}
		return $queryStr.';';
	}
	public function find($forceMaster = false){
		$res = $this->select($forceMaster);
		return current($res);
	}

	public function select($forceMaster = false){
		$dbInstance = $this->getDbLink();
        $this->queryStr = $this->getQueryStr('select');
		return $dbInstance->query($this->queryStr,$forceMaster);
	}

	public function update($data){
		$dbInstance = $this->getDbLink();
        $this->queryStr = $this->getQueryStr('update',$data);
		return $dbInstance->query($this->queryStr);
	}

	public function save($data){
		$dbInstance = $this->getDbLink();
        $this->queryStr = $this->getQueryStr('save',$data);
		return $dbInstance->query($this->queryStr);
	}

	public function delete(){
		$dbInstance = $this->getDbLink();
        $this->queryStr = $this->getQueryStr('delete');
		return $dbInstance->query($this->queryStr);
	}

	private function getDbLink(){
		return core_db::instance($this->databaseName);	
	}

}