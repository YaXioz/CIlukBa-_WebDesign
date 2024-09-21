<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Timeline;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{
    protected $post;
    protected $timeline;
    public function __construct()
    {
        $this->post = new Post();
        $this->timeline = new Timeline();
    }

    public function index()
    {
        $data = [
            'title' => 'Title',
            'posts' => $this->post->findAll(),
        ];

        return view('');
    }

    public function create()
    {
        $data = [
            'title' => 'title',
        ];
        return view('', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'image' => [
                'rules' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'errors' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tuliskan deskripsi'
                ]
            ],
            'event_date' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih tanggal'
                ]
            ]
        ])) {
            return redirect()->to('')->withInput();
        }

        $imageFile = $this->request->getFile('image');
        // $imageName = $imageFile->getError() == 4 ? 'default.jpg' : time() . '_' . $imageFile->getRandomName();
        $imageName = time() . '_' . $imageFile->getRandomName();
        $imageFile->move('assets/img/post', $imageName);

        $tanggal = $this->request->getVar('event_date');
        $tahun = date('Y', strtotime($tanggal));
        $timeline = $this->timeline->getTimeline(session()->get('id'), $tahun);

        if ($timeline != null) {
            session()->setFlashdata(['_ci_validation_errors' => ['event_date' => 'Timeline untuk tanggal tersebut belum tersedia, mohon cek kembali.']]);
            return redirect()->to('')->withInput();
        }

        $this->post->save([
            'timeline_id' => $timeline['id'],
            'url' => Uuid::uuid4()->toString(),
            'image' => $imageName,
            'description' => $this->request->getVar('description'),
            'event_date' => $tanggal,
        ]);

        return redirect()->to('');
    }

    public function detail($url)
    {
        $post = $this->post->getPost($url);

        //not found
        if ($post) return view('');

        $data = [
            'title' => 'title',
            'post' => $post,
        ];
        return view('', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'image' => [
                'rules' => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'errors' => [
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ]
            ],
            'description' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tuliskan deskripsi'
                ]
            ],
            'event_date' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih tanggal'
                ]
            ]
        ])) {
            return redirect()->to('')->withInput();
        }

        $imageFile = $this->request->getFile('image');
        if ($imageFile->getError() == 4) {
            $imageName = $this->request->getVar('imageNameLama');
        } else {
            $imageName = time() . '_' . $imageFile->getRandomName();
            $imageFile->move('assets/img/post', $imageName);
            unlink('assets/img/post/' . $this->request->getVar('imageNameLama'));
        }

        $tanggal = $this->request->getVar('event_date');
        $tahun = date('Y', strtotime($tanggal));
        $timeline = $this->timeline->getTimeline(session()->get('id'), $tahun);

        if ($timeline != null) {
            session()->setFlashdata(['_ci_validation_errors' => ['event_date' => 'Timeline untuk tanggal tersebut belum tersedia, mohon cek kembali.']]);
            return redirect()->to('')->withInput();
        }

        $this->post->save([
            'id' => $id,
            'timeline_id' => $timeline['id'],
            'url' => Uuid::uuid4()->toString(),
            'image' => $imageName,
            'description' => $this->request->getVar('description'),
            'event_date' => $tanggal,
        ]);

        return redirect()->to('');
    }

    public function delete($id)
    {
        $post = $this->post->find($id);
        if ($post['image'] != 'default.jpg')
            unlink('assets/img/post/' . $post['image']);
        $this->post->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('');
    }
}
