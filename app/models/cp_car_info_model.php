<?PHP

// * 获取yxp_cp库里的车辆信息 $

class Cp_car_info_model extends CI_Model
{
    private $cp;

    public function __construct()
    {   
        $this->load->helper('array');
        $this->cp = $this->load->database('yxp_cp', TRUE);
    }


    /**
     * 查询查客车辆信息
     * @param  int   $hy_carid 火眼id
     * @return array 返回查询数据
     * 
     */
    public function get_chake_carinfo($hy_carid)
    {
        return $this->cp->select('carid,carfullname,vin,branid,serieid,producerid,carbasicid,licensecar,taskid,licensprovince,licenscity,licenseyear,carbodycolor,mileage,source_from')
             ->get_where('chake_carinfo', array('carid' => $hy_carid))
             ->row_array();
    }

    /**
     * 查询车辆图片信息
     * @param  int    $taskid   检车任务id
     * @return string $img_path 车辆图片路径信息
     * 
     */
    public function get_img_path($taskid)
    {
        $img_info = $this->cp->select('filetype,filename')
                    ->get_where('chake_carfileinfo',array('taskid' => $taskid))
                    ->result_array();
        $img_path = '';
        if($img_info)
        {
            $img_info = array_set_key($img_info, 'filetype');
            $img_arr  = array();
            $img_type = array(6,51,52,33,53,54,55,56,57);
            for ($i=0; $i < count($img_type); $i++)
            { 
                if (isset($img_info[$img_type[$i]]))
                {
                    $img_arr[] = strval($img_info[$img_type[$i]]['filename']);
                }
            }

            $img_path = join(",",$img_arr);
        }

        return $img_path;

    }

    /**
     * 查克报告导入查询
     * @param  array  $where  条件 
     * @param  array  $limit  限制条数 
     * @param  string $result 返回类型'data'/数据 'count'/总条数 
     * @return array || int
     * 
     */
    public function get_detail_carinfo($where, $limit, $result='data')
    {
        $this->get_where_clause($where);
        $this->cp->select('*')
             ->from('chake_carinfo cc')
             ->join('chake_taskinfo ct', 'cc.taskid = ct.taskid')
             ->order_by('cc.carid' ,'asc');

        if($result == 'data')
        {
            if($limit)
            {
                $this->cp->limit($limit[1], $limit[0]);
            }

            return $this->cp->get()->result_array(); 
        }
        elseif($result == 'count')
        {
            return  $this->cp->get()->num_rows();
        }
    }

    /**
     * 组合条件
     * @param  array $where
     * @return void
     */
    private function get_where_clause($where)
    {
        $tmp_arr = array();
        $tmp_arr['ct.tvaid'] = $where['tvaid'];
        if($where['brand_id'])
        {
            $tmp_arr['cc.branid'] = intval($where['brand_id'])-2000000000;
        }
        if($where['trim_id'])
        {
            $tmp_arr['cc.carbasicid'] = intval($where['trim_id'])-2000000000;
        }
        if($where['series_id'])
        {   
            $tmp_arr['cc.serieid'] = intval($where['series_id'])-2000000000;
        }
        if($where['provinceid'])
        {
            $tmp_arr['cc.licensprovince'] = intval($where['provinceid']);
        }
        if($where['cityid'])
        {
            $tmp_arr['cc.licenscity'] = intval($where['cityid']);
        }
        if($where['no'])
        {
            $this->cp->like('cc.licensecar', $where['no']);
            //$w_sql .= " and cc.licensecar like '%".$this->db->escape_like_str($where['no'])."%'";
        }
        if($where['vin'])
        {
            $this->cp->like('cc.vin', $where['vin']);
            //$w_sql .= " and cc.vin like '%".$this->db->escape_like_str($where['vin'])."%'";
        }

        $this->cp->where($tmp_arr);
        unset($tmp_arr);
        $this->load->model('car_model');
        $vin = $this->car_model->get_car_vin();
        $vin = array_muliti_field($vin, 'vin');
        if($vin)
        {
            $this->cp->where_not_in('cc.vin', $vin);
        }   
        $this->cp->where_in('ct.status', array(1, 2));
        unset($vin);

    }


}
