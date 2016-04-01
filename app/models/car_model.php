<?PHP
// * $Id: car_model.php 24264 2014-07-08 07:15:59Z raohongfu $
class Car_model extends YXP_Model
{
	public function __construct()
	{
		parent::__construct('car' , 'carid');
	}

	/**
     * 查看报告是否已经被导入
     * @param  int $hy_carid 火眼id
     * @return int 
     * 
     */
    public function get_exists_bidding($hy_carid)
    {	
    	return $this->db->select('c.carid')
    		 		->join('publish p', 'c.carid=p.car_id')
    		 		->where(array('c.hy_carid' => $hy_carid, 'p.pisactive' => 1))
                    ->where_in('p.status', array(0,1))
    		 		->limit(1)
    		 		->get('car c')
    		 		->num_rows();
    }

    /**
     * 获取导入的车辆vin
     * @return array 
     * 
     */
    public function get_car_vin()
    {   
        return $this->db->select('c.vin')
                    ->join('publish p', 'c.carid=p.car_id')
                    ->where('p.pisactive', 1)
                    ->get('car c')
                    ->result_array();      
    }


}
