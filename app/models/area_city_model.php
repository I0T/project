<?PHP

class Area_city_model extends YXP_Model
{
	public function __construct()
	{
		parent::__construct('area_city', 'cityid');
		$this->load->database();
	}

    //根据 条件搜索城市
    public function search($data,$field="*") {
    	if($field != '*') {
    		$this->db->select($field);
    	}
        if($data['cityname']) {
            $this->db->where('cityname', $data['cityname']);
        }
        return $this->db->get('area_city')->result_array();
    }

    /**
     * 根据城市id查询城市名字
     * @param  int     $cityid 城市id
     * @return string  $cityname
     * 
     */
    public function address($cityid) {
        
        $this->db->select('C.cityname,P.provincename');
        if($cityid) {
            $this->db->from('area_city C');
            $this->db->join('area_province P','C.provinceid=P.provinceid');
            $this->db->where('cityid', $cityid);
        }
        $city = $this->db->get()->row_array();
        if($city['cityname'] == $city['provincename'])
        {
            $cityname = $city['cityname'];
        }
        else
        {
            $cityname = $city['provincename'].$city['cityname'];

        }

        return $cityname ;
    }



}
