<?php

use chriskacerguis\RestServer\RestController;

class Berita extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index_get($id = null)
    {
        $check_data = $this->db->get_where('berita', [
            'id_berita' => $id
        ])->row_array();
        if ($id) {
            if ($check_data) {
                $data = $this->db->get_where('berita', [
                    'id_berita' => $id
                ])->row_array();
                $this->response($data, RestController::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        } else {
            $data = $this->db->get('berita')->result();
            $this->response($data, RestController::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = array(
            'judul_berita' => $this->post('judul_berita'),
            'slug_berita' => strtolower(url_title($this->post('judul_berita'))),
            'deskripsi' => $this->post('deskripsi'),
            'kategori_id' => $this->post('kategori_id'),
            'created_by' => 'API',
        );
        $insert = $this->db->insert('berita', $data);
        if ($insert) {
            $this->response($data, RestController::HTTP_OK);
        } else {
            $this->response(array(
                'status' => 'failed'
            ), 502);
        }
    }

    public function index_put()
    {
        $id_berita = $this->put('id_berita');
        $data = array(
            'judul_berita' => $this->put('judul_berita'),
            'slug_berita' => strtolower(url_title($this->put('judul_berita'))),
            'deskripsi' => $this->put('deskripsi'),
            'kategori_id' => $this->put('kategori_id'),
            'modified_by' => 'API UPDATE',
        );
        $this->db->where('id_berita', $id_berita);
        $update = $this->db->update('berita', $data);
        if ($update) {
            $this->response($data, RestController::HTTP_OK);
        } else {
            $this->response(array(
                'status' => 'failed'
            ), 502);
        }
    }

    public function index_delete()
    {
        $id_berita = $this->delete('id_berita');
        $check_data = $this->db->get_where('berita', ['id_berita' => $id_berita])->row_array();

        if ($check_data) {
            $this->db->where('id_berita', $id_berita);
            $this->db->delete('berita');
            $this->response(array(
                'status' => 'success'
            ), 200);
        } else {
            $this->response(array(
                'status' => 'data not found'
            ), 404);
        }
    }
}
