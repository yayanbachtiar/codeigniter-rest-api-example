<?php
/* 
 * Generated by CRUDigniter v2.3 Beta 
 * www.crudigniter.com
 */
  require APPPATH . '/libraries/REST_Controller.php';

 
class Orders extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
    } 

    /*
     * Listing of orders
     */
    function index_get()
    {
        $data = $this->Order_model->get_all_orders();
		$this->response($data, REST_Controller::HTTP_OK);
    }

    function today_get()
    {
        $data =$this->Order_model->get_today_order();
        // echo $this->db->last_query();
        $this->response($data, REST_Controller::HTTP_OK);
    }

    /*
     * Adding a new order
     */
    function index_post()
    {   
        // $this->load->library('form_validation');
        $data = $this->input->post();
        if(!$data['pemesan']){
            $data['pemesan'] = '';
        }
        $count_order = $this->Order_model->get_count_order_today();
        $params = array(
            'nomor_order' => date('d/m/Y').'-ORD-'.($count_order['jumlah']+1),
            'tanggal' => date('Y-m-d H:i:s'),
            'meja' => $data['meja_id'],
            'pelayan' => $data['pelayan'],
            'pemesan'=> $data['pemesan']
        );
        
        $data = $this->Order_model->add_order($params);
        $data = $this->Order_model->get_order_by_id($data);
        $message = ["result"=> $data, 'message'=> "created", "code"=> true];            
        $this->set_response($message, REST_Controller::HTTP_CREATED);
    }

    public function view_get($order_id) {
        $order = $this->Order_model->get_order_by_id($order_id);
        $detail_order = $this->Order_model->get_order($order_id);
        $data = [
            "order"=>$order,
            "detail"=> $detail_order
        ];
		$this->response($data, REST_Controller::HTTP_OK);
    }

    public function bayar_put($id)
    {
        $data = ['is_pay'=> true];
        $message =  $this->Order_model->update_order($id, $data);
		$this->response($message, REST_Controller::HTTP_OK);
    }

    public function savepemesan_post($id)
    {
        $data = $this->input->post();
        $message = $this->Order_model->update_order($id, $data);
        $this->response($message, REST_Controller::HTTP_OK);
    }

    // /*
    //  * Editing a order
    //  */
    // function edit($id)
    // {   
    //     // check if the order exists before trying to edit it
    //     $order = $this->Order_model->get_order($id);
        
    //     if(isset($order['id']))
    //     {
    //         $this->load->library('form_validation');

	// 		$this->form_validation->set_rules('nomor_order','Nomor Order','required|max_length[100]');
	// 		$this->form_validation->set_rules('tanggal','Tanggal','required');
	// 		$this->form_validation->set_rules('meja','Meja','required|integer');
	// 		$this->form_validation->set_rules('pelayan','Pelayan','required|integer');
		
	// 		if($this->form_validation->run())     
    //         {   
    //             $params = array(
	// 				'nomor_order' => $this->input->post('nomor_order'),
	// 				'tanggal' => $this->input->post('tanggal'),
	// 				'meja' => $this->input->post('meja'),
	// 				'pelayan' => $this->input->post('pelayan'),
    //             );

    //             $this->Order_model->update_order($id,$params);            
    //             redirect('order/index');
    //         }
    //         else
    //         {   
    //             $data['order'] = $this->Order_model->get_order($id);
    
    //             $this->load->view('order/edit',$data);
    //         }
    //     }
    //     else
    //         show_error('The order you are trying to edit does not exist.');
    // } 

    /*
     * Deleting order
     */
    function remove($id)
    {
        $order = $this->Order_model->get_order($id);

        // check if the order exists before trying to delete it
        if(isset($order['id']))
        {
            $this->Order_model->delete_order($id);
            redirect('order/index');
        }
        else
            show_error('The order you are trying to delete does not exist.');
    }
    
}
