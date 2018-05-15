<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
  function __construct(){
      parent::__construct();
      $this->load->model('aromaterapi_model');
      $this->load->helper('url');
       if($this->session->userdata('status') != "login"){
         redirect(base_url("login"));
           $this->session->set_userdata('username','1');
       }

  }


  public function index($halaman='index'){
    $data['about']=$this->aromaterapi_model->get_aromaterapi('about');
    $data['kualitas']=$this->aromaterapi_model->get_aromaterapi('kualitas');
    $data['garansi']=$this->aromaterapi_model->get_aromaterapi('garansi');
    $data['ongkir']=$this->aromaterapi_model->get_aromaterapi('ongkir');
    $data['depan']=$this->aromaterapi_model->get_frontend();
    $data['testimoni']=$this->aromaterapi_model->get_testimoni();
    $data['batik']=$this->aromaterapi_model->get_aromaterapi('produk');
    $data['graf'] = $this->aromaterapi_model->get_counter();

    $this->load->view('templates/header');
    $this->load->view('admin/'.$halaman,$data);
    $this->load->view('templates/footer');
  }

   public function create(){
       $this->load->helper('form');
     $this->load->library('form_validation');


      $this->form_validation->set_rules('nama','nama','required');
      $this->form_validation->set_rules('harga','harga','required');

      $this->form_validation->set_rules('deskripsi','deskripsi','required');


        $nama = 'file_'.time();

       $config['upload_path']	 = './assets/img/uploads/';

       $config['allowed_types'] = 'gif|jpg|png';
       $config['max_size']		 = '5000';
       $config['max_width']	 = '5000';
       $config['max_height']	 = '5000';
       $config['file_name']	 = $nama;

     $this->load->library('upload', $config);


      if ($this->form_validation->run() == TRUE && $this->upload->do_upload('foto'))
       {

         $gambar = $this->upload->data();

         $data = array(
         'nama'=> $this->input->post('nama',TRUE),
         'harga'=> $this->input->post('harga',TRUE),
         'gambar'		=> $gambar['file_name'],
         'deskripsi'=> $this->input->post('deskripsi',TRUE)

       );

       $this->aromaterapi_model->insert($data);
       echo "
       <script>
         alert('data berhasil ditambahkan');
         window.location.href = '" . base_url() . "admin/table';
       </script>
       ";

       }  else   {
         $data['foto'] = '';
         if (! $this->upload->do_upload('foto'))
         {
            $error = array('error' => $this->upload->display_errors());

                         $this->load->view('admin/post', $error);
         }


   }
  }

  public function create1(){
      $this->load->helper('form');


       $nama = 'file_'.time();

      $config['upload_path']	 = './assets/img/uploads/';

      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size']		 = '5000';
      $config['max_width']	 = '5000';
      $config['max_height']	 = '5000';
      $config['file_name']	 = $nama;

    $this->load->library('upload', $config);


     if ($this->upload->do_upload('foto'))
      {

        $gambar = $this->upload->data();

        $data = array(
        'gambar'		=> $gambar['file_name'],
      );

      $this->aromaterapi_model->insert1($data);
      echo "
      <script>
        alert('data berhasil ditambahkan');
        window.location.href = '" . base_url() . "admin/datatest';
      </script>
      ";

      }  else   {
        $data['foto'] = '';
        if (! $this->upload->do_upload('foto'))
        {
           $error = array('error' => $this->upload->display_errors());
  $this->load->view('templates/header');
                        $this->load->view('admin/testimoni', $error);
        }


  }
 }





  public function update($id){
    $this->load->helper('form');
    $this->load->library('form_validation');

    $this->form_validation->set_rules('nama','nama','required');
    $this->form_validation->set_rules('harga','harga','required');
    $this->form_validation->set_rules('deskripsi','deskripsi','required');

    $nama = 'file_'.time();
    $config['upload_path']	 = './assets/img/uploads/';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size']		 = '5000';
    $config['max_width']	 = '5000';
    $config['max_height']	 = '5000';
    $config['file_name']	 = $nama;

    $this->load->library('upload', $config);

    if ($this->form_validation->run()=== FALSE) {
        $data['batik'] = $this->aromaterapi_model->get_batik_id($id,'produk');
        $this->load->view('templates/header');
        $this->load->view('admin/update',$data);
        $this->load->view('templates/footer');
    } else {
        if ($this->upload->do_upload('foto')) {
            $gambar = $this->upload->data();
            $data = array(
                'nama'=> $this->input->post('nama',TRUE),
                'harga'=> $this->input->post('harga',TRUE),
                'gambar'		=> $gambar['file_name'],
                'deskripsi'=> $this->input->post('deskripsi',TRUE)
            );
        } else {
            $data = array(
                'nama'=> $this->input->post('nama',TRUE),
                'harga'=> $this->input->post('harga',TRUE),
                'deskripsi'=> $this->input->post('deskripsi',TRUE)
            );
       }
        if ($this->aromaterapi_model->update_batik($id, $data) === TRUE ) {
             // $data['id'] = ;
             echo "
             <script>
               alert('data berhasil diubah');
               window.location.href = '" . base_url() . "admin/update/$id';
             </script>
             ";

        }else {
            echo "salah";
        }
    }
 }


 public function updateabout($id){
   $this->load->helper('form');
   $this->load->library('form_validation');
   $this->form_validation->set_rules('deskripsi','deskripsi','required');



   if ($this->form_validation->run()=== FALSE) {
       $data['about'] = $this->aromaterapi_model->get_batik_id($id,'about');
       $this->load->view('templates/header');
       $this->load->view('admin/updateabout',$data);
       $this->load->view('templates/footer');
   } else {
     $data = array(

         'deskripsi'=> $this->input->post('deskripsi',TRUE)
          );

       if ($this->aromaterapi_model->update_batik($id, $data,'about') === TRUE ) {
            // $data['id'] = ;
            echo "
            <script>
              alert('data berhasil diubah');
              window.location.href = '" . base_url() . "admin/about';
            </script>
            ";

      }else {
          echo "salah";
      }

       }
   }


   public function updateongkir($id){
     $this->load->helper('form');
     $this->load->library('form_validation');
     $this->form_validation->set_rules('judul','judul','required');
     $this->form_validation->set_rules('deskripsi','deskripsi','required');



     if ($this->form_validation->run()=== FALSE) {
         $data['ongkir'] = $this->aromaterapi_model->get_batik_id($id,'ongkir');
         $this->load->view('templates/header');
         $this->load->view('admin/updateongkir',$data);
         $this->load->view('templates/footer');
     } else {
       $data = array(
           'judul'=> $this->input->post('judul',TRUE),
           'deskripsi'=> $this->input->post('deskripsi',TRUE)
            );

         if ($this->aromaterapi_model->update_batik($id, $data,'ongkir') === TRUE ) {
              // $data['id'] = ;
              echo "
              <script>
                alert('data berhasil diubah');
                window.location.href = '" . base_url() . "admin/ongkir';
              </script>
              ";

        }else {
            echo "salah";
        }

         }
     }



     public function updategaransi($id){
       $this->load->helper('form');
       $this->load->library('form_validation');
       $this->form_validation->set_rules('judul','judul','required');
       $this->form_validation->set_rules('deskripsi','deskripsi','required');



       if ($this->form_validation->run()=== FALSE) {
           $data['garansi'] = $this->aromaterapi_model->get_batik_id($id,'garansi');
           $this->load->view('templates/header');
           $this->load->view('admin/updategaransi',$data);
           $this->load->view('templates/footer');
       } else {
         $data = array(
             'judul'=> $this->input->post('judul',TRUE),
             'deskripsi'=> $this->input->post('deskripsi',TRUE)
              );

           if ($this->aromaterapi_model->update_batik($id, $data,'garansi') === TRUE ) {
                // $data['id'] = ;
                echo "
                <script>
                  alert('data berhasil diubah');
                  window.location.href = '" . base_url() . "admin/garansi';
                </script>
                ";

          }else {
              echo "salah";
          }

           }
       }



       public function updatekualitas($id){
         $this->load->helper('form');
         $this->load->library('form_validation');
         $this->form_validation->set_rules('judul','judul','required');
         $this->form_validation->set_rules('deskripsi','deskripsi','required');



         if ($this->form_validation->run()=== FALSE) {
             $data['kualitas'] = $this->aromaterapi_model->get_batik_id($id,'kualitas');
             $this->load->view('templates/header');
             $this->load->view('admin/updatekualitas',$data);
             $this->load->view('templates/footer');
         } else {
           $data = array(
               'judul'=> $this->input->post('judul',TRUE),
               'deskripsi'=> $this->input->post('deskripsi',TRUE)
                );

             if ($this->aromaterapi_model->update_batik($id, $data,'kualitas') === TRUE ) {
                  // $data['id'] = ;
                  echo "
                  <script>
                    alert('data berhasil diubah');
                    window.location.href = '" . base_url() . "admin/kualitas';
                  </script>
                  ";

            }else {
                echo "salah";
            }

             }
         }




 public function ubah($id){
   $this->load->helper('form');
   $this->load->library('form_validation');



   $nama = 'file_'.time();
   $config['upload_path']	 = './assets/img/uploads/';
   $config['allowed_types'] = 'gif|jpg|png';
   $config['max_size']		 = '50000';
   $config['max_width']	 = '50000';
   $config['max_height']	 = '50000';
   $config['file_name']	 = $nama;

   $this->load->library('upload', $config);



       $data['testimoni'] = $this->aromaterapi_model->get_testi_id($id);
       $this->load->view('templates/header');
       $this->load->view('admin/update1',$data);
       $this->load->view('templates/footer');

       if ($this->upload->do_upload('foto')) {
           $gambar = $this->upload->data();
           $data = array(

               'gambar'		=> $gambar['file_name']

           );

            if ($this->aromaterapi_model->testimoni($id, $data) === TRUE ) {
            // $data['id'] = ;
            echo "
            <script>
              alert('data berhasil diubah');
              window.location.href = '" . base_url() . "admin/datatest';
            </script>
            ";
       }else {
        echo "
            <script>
              alert('data gagal diubah');
            </script>
            ";

       }


       }else {


       }
   }





 public function delete($id){
   $this->aromaterapi_model->delete_data($id,'produk');
   redirect('admin/table');
 }
 public function delete1($id){
   $this->aromaterapi_model->delete_data($id,'kontak');
   redirect('admin/kontak');
 }
 public function delete2($id){
   $this->aromaterapi_model->delete_data($id,'testimoni');
   redirect('admin/datatest');
 }






}
