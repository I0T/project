<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 拍卖大厅 * $Id: auction.php 26519 2014-08-21 02:45:43Z liqinggai $

class auction extends YXP_Controller {

    private $per_page = 12;

	function __construct() {
		parent::__construct();
        $this->load->model(array('publish_model'));

	}
    //拍卖大厅
    public function index()
    {   
        $this->publish_model->bidding_status();
        $this->load->model(array('car_model'));
        $get = $this->input->get();

        $ford = array();
        if ($get['brand_id'] && $get['series_id'])
        {
            try {
                $params = array(
                    array('type' => 'brand', 'key'  => 'iautos_brand_id',  'value' => $get['brand_id']),
                    array('type' => 'series','key'  => 'iautos_series_id', 'value' => $get['series_id']),
                    array('type' => 'mode',  'key'  => 'iautos_series_id', 'value' => $get['series_id']),
                );
                $result = $this->publish_model->get_car_type($params);
                unset($params);
                $seriesYes = false;
                $seriesRes = array();
                foreach ($result['series'] as $series => $ser)
                {
                    foreach ($ser as $s)
                    {
                        if ($s['iautos_series_id'] == $get['series_id'])
                        {
                            $seriesRes = $s;
                            $seriesYes = true;
                            break;
                        }
                    }
                    if ($seriesYes)
                    {
                        break;
                    }
                }
                $modeYes = false;
                $modeRes = array();
                foreach ($result['mode'] as $modes)
                {
                    foreach ($modes as $m)
                    {
                        if (intval($m['iautos_mode_id']) == intval($get['trim_id']))
                        {
                            $modeRes = $m;
                            $modeYes = true;
                            break;
                        }
                    }
                    if ($modeYes)
                    {
                        break;
                    }
                }

                $ford = array(
                    'serial_name' => strval($result['brand']['iautos_brand_name']),
                    'model_name'  => strval($seriesRes['iautos_series_name']),
                    'trim_name'   => strval($modeRes['cxk_mode_name']),
                    'brand_id'    => $get['brand_id'],
                    'series_id'   => $get['series_id'],
                    'trim_id'     => $get['trim_id'],
                );

                $this->smarty->assign('ford', $ford);
                unset($ford);
            } 
            catch (Exception $e)
            {
                
            }
        }
        
        $page  = max(1, $get['page']);
        $this->per_page = 5;
        $limit = "";
        $get['status'] = 1;
        $total_rows    = $this->publish_model->search_bid($get, $limit, 'count');
        $begin         = ($page-1)*$this->per_page + 1;
        $end           = $begin + $this->per_page - 1;     
        $limit         = array($begin, $end);
        $publish_data  = $this->publish_model->search_bid($get, $limit, 'data');
        $show_data     = array();
        $countDowns    = array();

        if($publish_data)
        {
            $publishids = array_muliti_field($publish_data, 'publishid');
            $uid        = $this->y_user['userid'];
            $publish_bid_data = $this->db->select_max('price')
                ->select('publish_id')
                ->where(array('uid' => $uid, 'type' => 1))
                ->where_in('publish_id', $publishids)
                ->group_by('publish_id')
                ->get('publish_bid')->result_array();

            $publish_bid_data = array_set_key($publish_bid_data,'publish_id');
            foreach($publish_data as $key=>$val)
            {    
                if($publish_bid_data[$val['publishid']])
                {
                    $publish_data[$key]['myprice'] = $publish_bid_data[$val['publishid']]['price'];
                }
                else
                {
                    $publish_data[$key]['myprice'] = "未参与投标";
                }
                $bid_time_show = $this->_is_auction_time($val['bid_time'],$val['start_time']);
                $publish_data[$key]['bid_time_show'] = $bid_time_show['status'];
                $publish_data[$key]['car_img']       = strstr($publish_data[$key]['car_base_img'], ',', true);
                $show_data[$publish_data[$key]['bid_time']]['data'][] = $publish_data[$key];
                $show_data[$publish_data[$key]['bid_time']]['type']   = $bid_time_show['status'];

                if (count($show_data[$publish_data[$key]['bid_time']]['data']) == 1)
                {   
                    $bid_time  = $publish_data[$key]['bid_time'];
                    
                    if($publish_data[$key]['bidding_status'] == 0)
                    {
                        $countdown = $this->bid_time_to_time($bid_time);// 竞价开始时间
                    }
                    elseif ($publish_data[$key]['bidding_status'] == 1)
                    {
                        $countdown = intval($publish_data[$key]["end_time"]) - 60;// 等待竞价时间
                    }
                    elseif ($publish_data[$key]['bidding_status'] == 2) // 竞价结束时间
                    {
                        $countdown = $publish_data[$key]["end_time"];
                    }

                    if($bid_time_show['status'] == 3)
                    {
                        $countdown = $val['start_time'];// 投标开始时间
                    }

                    $countdown = $countdown - time(); // 剩余时间
                    $countdown_show            = "<i id='$countdown'></i>";
                    $countDowns[$bid_time]     = $countdown_show;
                    $bidding_status[$bid_time] = $publish_data[$key]['bidding_status'];
                }
            }
            
        }
        //分页
        $this->load->config('page', true);
        $this->smarty->assign('bidding_status', $bidding_status);
        $this->smarty->assign('countDowns', $countDowns);
        $config = $this->config->item('page');
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = !is_array($total_rows) && $total_rows > 0 ? $total_rows : 0;
        $config['per_page']   = $this->per_page;
        unset($get['page']);
        $config['base_url']   = '?'.http_build_query($get?$get:array());
        $this->pagination->initialize($config);
        $links = $this->pagination->create_links();
        $this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
        $this->smarty->assign('links', $links);
        $this->smarty->assign('get', $get);
        $this->smarty->assign('show_data', $show_data);
        $this->smarty->display('dealer/auction.html');
    }
    
    //竞价页面
    public function bidding()
    {   
        $get = $this->input->get();
        $this->load->model(array('car_model','publish_bid_model'));
        $this->publish_model->bidding_status();
        $publish_data = $this->publish_model->get_one($get['pid']);//发拍信息
        $car_data = $this->car_model->get_one($publish_data['car_id']);//车信息
        $top_bid  = $this->publish_bid_model->get_row(array('publish_id' => intval($get['pid']), 'type' => 1),'price', 'DESC', 1);//最高投标价格
        $my_last_bidding = $this->publish_bid_model->get_row(array('publish_id' => intval($get['pid']), 'uid' => $this->y_user['userid'], 'type' => 2), 'bid', 'DESC', 1);

        if($publish_data['status'] > 1)
        {  
            //最高的投标方式
            $top_bid_type = $this->publish_bid_model->get_row(array('bid' => $publish_data['publish_bid']),'price', 'DESC', 1);
            $publish_data['top_bid_type'] = $top_bid_type['type'];
        }
        if($top_bid_type['uid'] == $this->y_user['userid'])
        {
            $publish_data['mybid'] = $top_bid_type['bid'];
        }
        else
        {
            $publish_data['mybid'] = $my_last_bidding['bid'];
        }
        
        //竞价出价 start
        if ($get['type']=='bidding')
        {
            if ($publish_data['status'] != 1) 
            {
                $this->ajax_return(array('status' => 0 ,'msg' => '不在竞价状态中'));
            }
            if ($publish_data['end_time']-time() > 60 || $publish_data['end_time']-time() < 0) 
            {
                $this->ajax_return(array('status' => 0 , 'msg' => '不是竞价时间'));
            }
            if (floatval($get['curr_price']) != floatval($publish_data['start_price']) && floatval($get['curr_price']) != floatval($publish_data['top_price']))
            {   
                $this->ajax_return(array('status' => 0,'msg' => '出价失败'));
                
            }

            //自己是当前最高价，超过保留价或者是投标最高价不能在竞价
            if ($publish_data['publish_bid'] == $my_last_bidding['bid'])
            {   
                $stop_bidding_price = $top_bid['price']>$publish_data['price'] ? $top_bid['price'] : $publish_data['price'];
                if(floatval($stop_bidding_price) <  floatval($get['curr_price']))
                {
                    $this->ajax_return(array('status' => 0 , 'msg' => '出价失败,您已是最高价!'));
                }
            }
            
            try{//异常捕获

                if($publish_data['publish_bid'])
                {
                    $top_price = $publish_data['top_price'];
                }
                else
                {   
                    $top_price = $publish_data['start_price'];
                }

                $price              = $get['price']/10000;                
                $data['price']      = $price + $top_price;
                $data['type']       = 2;//竟价
                $data['uid']        = $this->y_user['userid'];
                $data['total_price']= $this->_calc_total_price($data['price'], $charge_price);
                $data['publish_id'] = intval($get['pid']);
                $data['dealer_id']  = $this->y_user['dealer_id'];
                $data['createtime'] = time();

                $this->db->trans_begin();
                //取最近一条出价记录
                $count_bid          = $this->publish_bid_model->get_row(array('publish_id' => intval($get['pid']), 'type' => 2),'bid', 'DESC', 1);
                $data['count_bid']  = $count_bid['count_bid'] + 1;
                //如果该价格有人出过就失败
                if($count_bid['price'] < $data['price'])
                {    
                    $bidding_id = $this->publish_bid_model->save($data);
                }
                else
                {
                    $this->ajax_return(array('status' => 0,'msg' => '出价失败'));
                }
                
                if($bidding_id)
                {   
                    $remain_time = $publish_data["end_time"] - time();
                    //倒计时小于10秒时，有人出价则重新置10秒
                    if($remain_time < 10)
                    {
                        $countdown = 10;//剩余时间                
                        $add_time  = 10 - $remain_time;
                        //排队中的要加上多于的时间
                        $this->db->set('end_time', "`end_time`+$add_time", FALSE)
                             ->update('publish', array(), array('bid_time' => $publish_data['bid_time']));
                    } 
                    else
                    {   
                        $countdown = $remain_time;//剩余时间
                    }
               
                    //如果还没有人竞价最高价是0.00
                    if($get['curr_price'] == $publish_data['start_price'])
                    {
                        $get['curr_price'] ='0.00';
                    }
                    //更新拍品的当前价格
                    $res = $this->publish_model->update(array('top_price' => $data['price'], 'publish_bid' => $bidding_id), array('publishid' => $get['pid'],'top_price' => $get['curr_price']));
                    if(!$res)
                    {
                        $this->db->trans_rollback();
                        $this->ajax_return(array('status' => 0,'msg' => '出价失败'));
                    }

                    if($this->db->trans_status()===TRUE)
                    {
                        $this->db->trans_commit();
                    }
                    else
                    {
                        $this->db->trans_rollback();
                        $this->ajax_return(array('status' => 0,'msg' => '出价失败'));
                    }
                    //$json_data['countdown_show']='<em class="blue">'. floor($countdown/(3600*24)).'</em>天<em class="blue">'.floor(($countdown%(3600*24))/3600).'</em>小时<em class="blue">'.floor(($countdown%(3600*24*3600)/60)).'</em>分钟';
                    $json_data = array();               
                    $json_data['countdown']       = $countdown.'秒';
                    $json_data['curr_price']      = $data['price'];
                    $json_data['myprice']         = $data['price'];
                    $json_data['bid_times']       = $data['count_bid'];
                    $json_data['total_price']     = $data['total_price'];
                    $json_data['charge_price']    = $charge_price;
                    $json_data['refresh_status']  = 1;//刷新成功
                    $this->ajax_return($json_data);
                }
                else
                {
                    $this->db->trans_rollback();
                    $this->ajax_return(array('status' => 0,'msg' => '出价失败'));
                }
            }catch(Exception $e){//异常捕获
                echo 0;
                exit;
            }
        }//竞价出价end
        
        if($publish_data)
        {    
            //图片
            if($car_data['car_base_img'])
            {
                $car_data['car_base_img'] = explode(",", $car_data['car_base_img']);
            }
            $bid_time_show = $this->_is_auction_time($publish_data['bid_time'], $publish_data['start_time']);
            if ($bid_time_show['status'] == 1)//拍卖中
            {
                if($publish_data['publish_bid'])//有人竞价
                {
                    $publish_data['curr_price'] = $publish_data['top_price'];
                }
                else
                {
                    $publish_data['curr_price'] = $publish_data['start_price'];
                }
            }
            else
            {
                $publish_data['curr_price'] = '--';
                show_error('对不起，访问出错，请点击返回！', 200, '没有权限');
            }
            
            //我的投标
            $my_last_bid = $this->publish_bid_model->get_row(array('publish_id' => $get['pid'], 'uid' => $this->y_user['userid'], 'type' => 1), 'bid', 'desc', 1);
            
            if($my_last_bidding)
            {
                $publish_data['myprice']= $my_last_bidding['price'];
                //合手价等于 当前价 + (当前价*1% 不足1000按1000,超过3000按3000)
                $publish_data['total_price'] = $this->_calc_total_price($my_last_bidding['price'], $publish_data['charge_price']);
            }
            else
            {   
                $publish_data['myprice']="--";
            }

            if ($my_last_bid)
            {
               $publish_data['my_bid_price']= $my_last_bid['price'];
            }
            else 
            {
                $publish_data['my_bid_price']= '--';
            }
            //最后成交合手价
            $publish_data['over_total_price'] = $this->_calc_total_price($publish_data['top_price'], $publish_data['over_charge_price']);
            //出价手次
            $bid_times = $this->publish_bid_model->get_all(array('publish_id'=>$get['pid'], 'type' => 2), 'bid', 'desc');
            $publish_data['bid_times'] = count($bid_times);
            
            //竞价中的上下页start
            $order = $this->publish_model->get_col(array('bid_time' => $publish_data['bid_time']), 'order');
            $publishing = $this->publish_model->get_row(array('bid_time' => $publish_data['bid_time'], 'status' => 1));
            if(!empty($order) && !empty($publishing))
            {
                $max_order  = max($order);
                $min_order  = min($order);
                $order      = array_flip($order);
                if($publish_data['order'] < $max_order)
                {
                    $publish_data['next_publishid'] = $order[$publish_data['order'] + 1];//下一拍品id
                }
                if($publish_data['order'] > $min_order)
                {
                    $publish_data['pre_publishid'] = $order[$publish_data['order'] - 1];
                }
            }//竞价中的上下页end
        }
        //竞价倒计时
        $remain_time = $publish_data["end_time"] - time();
        if($publish_data['bidding_status']==1)//等待竞价中
        {
            $remain_time = $remain_time - 60;
        }
        $countdown_hour   = floor($remain_time / 3600);    
        $countdown_minute = floor(($remain_time % 3600)/60);
        $countdown_second = floor(($remain_time % 3600)%60);
        if($countdown_hour > 0)
        {
            $countdown_msg = "竞价开始倒计时";
            $countdown_ch  = $countdown_hour."小时".$countdown_minute.'分'.$countdown_second.'秒';
        }
        elseif($countdown_minute > 0)
        {
            $countdown_msg = "竞价开始倒计时";
            $countdown_ch  = $countdown_minute."分钟".$countdown_second."秒";
        }
        else
        {
            if($publish_data['bidding_status']==2)
            {
                $countdown_msg = "竞价开始<br/>竞价结束倒计时";
            } 
            else
            {
                $countdown_msg = "等待竞价中<br/>竞价开始倒计时";

            }
            $countdown_ch  = $countdown_second."秒";
        } 

        $publish_data['countdown_show'] = $countdown_ch;
        $publish_data['countdown_msg']  = $countdown_msg;

        //刷新页面start
        if($get['type']=='refresh')
        {   if ($publish_data['status'] != 1) 
            {
                $this->ajax_return(array('status' => 0));
            }
            //超过保留价
            if($publish_data['price'] < $publish_data['top_price'])
            {
                $publish_data['over_price'] = 1;
            }
            //超过投标最高价
            if($publish_data['pre_bid_price']!='0.00' && $publish_data['pre_bid_price'] < $publish_data['top_price'])
            {
                $publish_data['over_bid_price'] = 1;
            }

            //竞价中
            $publish_data['bid_list']=$this->publish_model->get_bid_list($get['pid'],$publish_data['publish_bid'],5);
            $publish_data['refresh_status'] = 1;
            $this->ajax_return($publish_data);
        }//刷新页面end

        $this->load->config('dict');
        $this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
        $this->smarty->assign('publish_data', $publish_data);
        $this->smarty->assign('car_data', $car_data);
        $this->smarty->display('dealer/auction_bidding.html');
       
    }

    // 投标
    public function bid()
    {
        $get=$this->input->get();
        $this->load->model(array('car_model','publish_bid_model'));
        $publish_data  = $this->publish_model->get_one($get['pid']);
        $bid_time_show = $this->_is_auction_time($publish_data['bid_time'], $publish_data['start_time']);
        $my_bid = $this->publish_bid_model->get_row(array('publish_id' => intval($get['pid']), 'uid' => $this->y_user['userid'], 'type' => 1), 'bid', 'DESC', 1);
        /*** 投标begin ***/
        if($get['type']=='bid')
        {
            if ($this->y_user['type'] == 4)
            {
                exit(0);
            }
            
            try{//异常捕获
                
                if($bid_time_show['status'] != 2)
                {
                    $this->ajax_return(array('status' => 0, 'msg' => '不在投标时间内'));
                }
                if($my_bid) 
                {
                    $this->ajax_return(array('status' => 0, 'msg' => '只能投一次标'));
                }
                
                if ($publish_data['status'] !=1 || $publish_data['end_time']-time() <= 0)
                {
                    $json_data = array('status' => 0, 'msg' => '失败');
                    $this->ajax_return($json_data);
                    
                }

                if ($publish_data['start_price'] >= $get['price'])
                {
                    $this->ajax_return(array('status' => 0, 'msg' => '投标失败,投标金额要大于起拍价!'));
                }
                //所有的投标记录
                $bid = $this->publish_bid_model->get_col(array('publish_id' => $get['pid'], 'type' => 1), 'price');
                $bid = array_values($bid);
                // 重复的价位不能投
                if(in_array($get['price'], $bid))
                {
                    $this->ajax_return(array('status' => 0, 'msg' => '投标失败,该价位已有人投!'));
                }
                //取最近一条投标记录
                $count_bid           = $this->publish_bid_model->get_row(array('publish_id' => intval($get['pid']), 'type' => 1),'bid', 'DESC', 1);
                $data['price']       = $get['price'];
                $data['type']        = 1;//投标
                $data['total_price'] = $get['total_price'];
                $data['publish_id']  = intval($get['pid']);
                $data['dealer_id']   = $this->y_user['dealer_id'];
                $data['uid']         = $this->y_user['userid'];
                $data['count_bid']   = $count_bid['count_bid']+1;
                $data['createtime']  = time();

                $res = $this->publish_bid_model->save($data);
                if($res)
                {   if($publish_data['pre_bid_price'] < $data['price'])
                    {
                        $this->publish_model->update(array('pre_bid_price' => $data['price'],'pre_bid_id' => $res), array('publishid' => $get['pid']));
                    }
                    $countdown = $publish_data["end_time"]-time();//投标剩余时   
                    $json_data = array();             
                    $json_data['countdown']  = $countdown;
                    $json_data['curr_price'] = $data['price'];
                    $json_data['myprice']    = $data['price'] ? $data['price'] : '您未参与投标';
                    $json_data['status']     = 1;//刷新成功
                    $json_data['msg']        = '投标成功!';
                    $this->ajax_return($json_data);
                    
                }
                else
                {
                   $this->ajax_return(array('status' => 0));
                }
            }
            catch(Exception $e)
            {//异常捕获
                $this->ajax_return(array('status' => 0));
            }
        }/*** 投标end   ***/

        if($publish_data)
        {    
            //车信息
            $car_data = $this->car_model->get_one($publish_data['car_id']);
            //图片
            if($car_data['car_base_img'])
            {
                $car_data['car_base_img'] = explode(",", $car_data['car_base_img']);
            }
            $publish_data['type'] = $bid_time_show['status'];
            //我的投标价
            $pid_data = $this->publish_bid_model->get_all(array('publish_id' => $get['pid'],'uid' => $this->y_user['userid'], 'type' => 1), 'bid', 'desc', 1);
            $pid_data = current($pid_data);
            $publish_data['myprice'] = $pid_data['price'] ? $pid_data['price'] : 0;
            $publish_data['total_price'] = $this->_calc_total_price($pid_data['price'], $publish_data['charge_price']);
            if($my_bid) //已经投标
            {
                $publish_data['bided'] = 1;
            }
        }
        
        $bid_time    = $this->bid_time_to_time($publish_data["bid_time"]);
        $remain_time = $bid_time - time();
        if($bid_time_show['status'] ==3)
        {
            $remain_time = $publish_data['start_time']-time();

        }

        $countdown_ch = "<i id='$remain_time'></i>";
        $this->load->config('dict');
        $this->smarty->assign('publish_data', $publish_data);
        $this->smarty->assign('car_data', $car_data);
        $this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
        $this->smarty->assign('countdown_ch',$countdown_ch);//剩余时间
        $this->smarty->assign('countdown_msg',$countdown_msg);
        $this->smarty->display('dealer/auction_bid.html');
       
    }

    //竞拍管理(我出价的车)
    public function owner_bid()
    {
        $this->load->helper(array('array'));
        $this->load->model(array('publish_bid_model','car_model'));
        $get = $this->input->get();
        
        $ford = array();
        if ($get['brand_id'] && $get['series_id']) {
            try {
                $params = array(
                    array('type' => 'brand',  'key'  => 'iautos_brand_id',  'value' => $get['brand_id']),
                    array('type' => 'series', 'key'  => 'iautos_series_id', 'value' => $get['series_id']),
                    array('type' => 'mode',   'key'  => 'iautos_series_id', 'value' => $get['series_id']),
                );
                $result = $this->publish_model->get_car_type($params);

                $seriesYes = false;
                $seriesRes = array();
                foreach ($result['series'] as $series => $ser) {
                    foreach ($ser as $s) {
                        if ($s['iautos_series_id'] == $get['series_id']){
                            $seriesRes = $s;
                            $seriesYes = true;
                            break;
                        }
                    }
                    if ($seriesYes) {
                        break;
                    }
                }
                $modeYes = false;
                $modeRes = array();
                foreach ($result['mode'] as $modes) {
                    foreach ($modes as $m) {
                        if (intval($m['iautos_mode_id']) == intval($get['trim_id'])) {
                            $modeRes = $m;
                            $modeYes = true;
                            break;
                        }
                    }
                    if ($modeYes) {
                        break;
                    }
                }
                $ford = array(
                    'serial_name' => strval($result['brand']['iautos_brand_name']),
                    'model_name'  => strval($seriesRes['iautos_series_name']),
                    'trim_name'   => strval($modeRes['cxk_mode_name']),
                    'brand_id'    => $get['brand_id'],
                    'series_id'   => $get['series_id'],
                    'trim_id'     => $get['trim_id'],
                );
                
                $this->smarty->assign('ford', $ford);
            } catch (Exception $e) {
                
            }
        }

        $this->per_page = 10;
        $where = $get;
        $page  = max(1, $get['page']);
        $limit = array(($page - 1)*$this->per_page, $this->per_page);
        $where['uid'] = $this->y_user['userid'];
        $this->smarty->assign('get', $get);

        if($get['type']=='export') 
        {// 导出不分页
            $publish_data = $this->publish_bid_model->get_my_bid($where, '', 'data');
        }
        else
        {
            $publish_data = $this->publish_bid_model->get_my_bid($where, $limit, 'data');
        }

        if($publish_data)
        {   
            $publish_ids = array_muliti_field($publish_data, 'publish_id');
            $bid_data    = $this->publish_bid_model->get_all(array('publish_id' => $publish_ids, 'type' =>1, 'uid'=> $this->y_user['userid']));
            $bid_data    = array_set_key($bid_data, 'publish_id');
            foreach ($publish_data as $k => $v) {

                $publish_data[$k]['total_price'] = $publish_data[$k]['total_price'];
                if($v['type']==1)
                {
                    $publish_data[$k]['type'] = '投标';
                    $publish_data[$k]['bid_price'] = $v['price'];
                    $publish_data[$k]['bidding_price'] = '您未竞价';

                } 
                else
                {   
                    $publish_data[$k]['bid_price'] = $bid_data[$v['publish_id']]['price'] ? $bid_data[$v['publish_id']]['price'] : '您未投标';
                    $publish_data[$k]['bidding_price'] = $v['price'];
                    $publish_data[$k]['type'] = '竞价';
                }

                if($v['status']==3)
                {   
                    if($v['bid']==$v['publish_bid'] || $v['publish_bid']==$bid_data[$v['publish_id']]['bid'])
                    {//竞价或者是投标中标的

                        $publish_data[$k]['type'] = '你已竞得';
                    } 
                    else 
                    {
                        $publish_data[$k]['type'] = '你未竞得';
                    }
                }
                elseif($v['status']==1)
                {
                    $bid_time_show = $this->_is_auction_time($v['bid_time'], $v['start_time']);
                   
                    if($bid_time_show['status']==2)
                    {
                        $type = '投标中';
                    }
                    elseif($bid_time_show['status']==1)
                    {
                        if($v['bidding_status']==2)
                        {
                            $type = '竞价中';
                        }
                        else
                        {
                            $type = '等待竞价';
                        }
                    }
                    else
                    {
                        $type = '';
                    }

                    $publish_data[$k]['type'] = $type;
                }
                else
                {//流拍
                    $publish_data[$k]['type'] = '你未竞得';
                }

            }
        }

        if($get['type']=='export')
        {
            //导出excel
            ini_set('memory_limit', '1024M');
            $this->exportexcel(1, $publish_data, 'publish.xls');
        }
        //分页
        $this->load->config('page', true);
        $this->load->library(array('pagination'));
        $config = $this->config->item('page');
        $config['page_query_string'] = TRUE;
        $total_rows = $this->publish_bid_model->get_my_bid($where, '', 'count');
        $config['total_rows'] = !is_array($total_rows) && $total_rows > 0 ? $total_rows : 0;
        $config['per_page'] = $this->per_page;
        unset($get['page']);
        $config['base_url'] = '?'.http_build_query($get?$get:array());
        $r= $this->pagination->initialize($config);
        $links = $this->pagination->create_links();
        $this->smarty->assign('links', $links);
        $this->load->config('dict', true);
        $this->smarty->assign('dict_domain_chake', $this->config->item('dict_domain_chake'));
        $this->smarty->assign('publish_data', $publish_data);
        $this->smarty->display('dealer/auction_owner_bid.html');
    }
    

    //出价记录
    function publish_bid() {
        $get = $this->input->get();
        $this->load->model(array('publish_bid_model'));
        if($get['type']=='export')
        {
            //导出excel
            ini_set('memory_limit', '1024M');
            $bid = $this->publish_bid_model->record($get['pid']);
            foreach($bid as $key=>$val){
                if($val['userid'] != $this->y_user['userid']){
                    $bid[$key]['dealer_name'] = "---";
                }
            }
            $this->exportexcel(2, $bid, 'bid.xls');
        }
        $bid = $this->publish_bid_model->record($get['pid']);
        if($bid && !empty($bid)){

            foreach($bid as $key=>$val){
                if($val['userid'] != $this->y_user['userid']){
                    $bid[$key]['dealer_name'] = "---";
                }
            }
        }

        $this->smarty->assign('bid', $bid);
        $this->smarty->assign('pid', $get['pid']);
        $this->smarty->display('dealer/publish_bid.html');
    }

    /**
     * 导出
     * @param  int     标识
     * @param  array   数据 
     * @param  string  文件名
     * @return void
     */
    private function exportexcel($sign, $data, $filename){        
        $this->load->model(array('car_model'));
        $exceldata = array();
        if($sign == 1)
        {   
            $this->smarty->assign('publish_data',$data);
            $exceldata = $this->smarty->fetch('dealer/export.html');
            
        }
        elseif($sign == 2)
        {
            $i=1;
            foreach ($data as $k=>$d)
            {
                $exceldata[$k]['car_name']    = $d['car_name'];
                $exceldata[$k]['area_name']   = $d['prov_name'].'-'.$d['city_name'];
                $exceldata[$k]['dealer_name'] = $d['dealer_name'];
                $exceldata[$k]['pstart_time'] = $d['pstart_time'];
                $exceldata[$k]['pcreatetime'] = $d['pcreatetime'];
                $exceldata[$k]['bcreatetime'] = $d['bcreatetime'];
                $exceldata[$k]['no']    = $d['no'];
                $exceldata[$k]['vin']   = $d['vin'];
                $exceldata[$k]['pn']    = $d['pn'];
                $exceldata[$k]['price'] = $d['price'];
                $exceldata[$k]['type']  = $d['type']==1 ? '投标' : '竞价';
                $exceldata[$k]['id']    = $i;
                $i++;
            }
            $this->smarty->assign('data', $exceldata);
            $exceldata = $this->smarty->fetch('admin/cj_history.html'); 
        }
        else{
            $this->smarty->assign('data', $data);
            $exceldata = $this->smarty->fetch('dealer/all_bid.html');            
        }
        $this->load->helper('download');
        force_download($filename, $exceldata);
        exit;
    }

    //展示投标记录
    function bid_records(){
        $get=$this->input->get();
        $this->smarty->assign('get',$get);
        $this->smarty->assign('bid_list',$this->publish_model->get_bid_list($get['pid']));
        $this->smarty->display("dealer/bid_record.html");
    }
    
    /**
     * 计算合手价和佣金
     * @param  string  $price 拍卖时间场次
     * @param  string  $cp 投标开始时间
     * @return string  合手价
     */
    private function _calc_total_price($price, &$cp) {
        if (!$price) {
            return $price;
        }
        
        $price = $price * 10000;
        if ($price < 100000)
        {
            $pr = 1000;
        }
        elseif ($price > 300000)
        {
            $pr = 3000;
        }
        else
        {
            $pr = $price * 0.01;
        }
        $cp = sprintf("%.2f", round($pr/100)/100);
        return sprintf("%.2f", round(($price + $pr)/100)/100);
    }
     
    /**
     * 是否到拍卖时间
     * @param  string  $bid_times 拍卖时间场次
     * @param  string  $start_bid_time 投标开始时间
     * @return status 
     */
    private function _is_auction_time($bid_times='', $start_bid_time) {
        if ($bid_times != '') {
            $y = substr($bid_times, 0, 4);
            $m = substr($bid_times, 4, 2);
            $d = substr($bid_times, 6, 2);
            $h = substr($bid_times, 8, 2);
            $datetime = $y.'-'.$m.'-'.$d.' '.$h.':00:00';
            if (time() >= strtotime($datetime)) {
                $status = 1;// 竞价开始
            } elseif (time() >= $start_bid_time) {
                $status = 2; // 投标开始
            } else {
                $status = 3; //预展期
            }
        } else {
            $status = -1;//失败
        }

        return array('status' => $status);
    }

    /**
     * 拍卖场次转换成时间戳
     * @param  string  $bid_times 拍卖时间场次
     * @return string  时间戳
     */
    public function bid_time_to_time($bid_times)
    {
        if ($bid_times != '')
        {
            $y = substr($bid_times, 0, 4);
            $m = substr($bid_times, 4, 2);
            $d = substr($bid_times, 6, 2);
            $h = substr($bid_times, 8, 2);
            $datetime = $y.'-'.$m.'-'.$d.' '.$h.':00:00';
            $time = strtotime($datetime);
        }
        else
        {
            $time = '';
        }
        return $time;
    }     
    
}