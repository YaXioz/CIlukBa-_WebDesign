<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Timeline;
use CodeIgniter\HTTP\ResponseInterface;

class TimelineController extends BaseController
{
    protected $timeline;
    public function __construct()
    {
        $this->timeline = new Timeline();
    }

    public function index()
    {
        $data = [
            'title' => 'Title',
            'posts' => $this->timeline->findAll(),
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
        $this->validator->setRules(
            [
                'year' => [
                    'required',
                    static fn($value) => $this->timeline->getTimeline(session()->get('id'), $value) == null,
                ],
                'image_1' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'image_2' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'image_3' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
            ],
            [
                'year' => [
                    'required' => 'Pilih file terlebih dahulu',
                    1 => 'Tahun yang dipilih telah memiliki timeline'
                ],
                'image_1' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
                'image_2' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
                'image_3' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
            ]
        );
        if ($this->validator->run([
            'year'   => $this->request->getVar('year'),
            'image_1' => $this->request->getFile('image_1'),
            'image_2' => $this->request->getFile('image_2'),
            'image_3' => $this->request->getFile('image_3'),
        ])) return redirect()->to('')->withInput();

        // 1
        $imageFile1 = $this->request->getFile('image_1');
        $imageName1 = time() . '_' . $imageFile1->getRandomName();
        $imageFile1->move('assets/img/timeline', $imageName1);
        // 2
        $imageFile2 = $this->request->getFile('image_2');
        $imageName2 = time() . '_' . $imageFile2->getRandomName();
        $imageFile2->move('assets/img/timeline', $imageName2);
        // 3
        $imageFile3 = $this->request->getFile('image_3');
        $imageName3 = time() . '_' . $imageFile3->getRandomName();
        $imageFile3->move('assets/img/timeline', $imageName3);

        $this->timeline->save([
            'user_id' => session()->get('id'),
            'year' => $this->request->getVar('year'),
            'image_1' => $imageName1,
            'image_2' => $imageName2,
            'image_3' => $imageName3,
        ]);

        return redirect()->to('');
    }

    public function detail($year)
    {
        $timeline = $this->timeline->getTimeline(session()->get('id'), $year);

        //not found
        if ($timeline) return view('');

        $data = [
            'title' => 'title',
            'timeline' => $timeline,
        ];
        return view('', $data);
    }

    public function update($id)
    {
        if ($this->request->getVar('year') == $this->request->getVar('yearOld')) {
            $rule = $this->timeline->getTimeline(session()->get('id'), $this->request->getVar('year')) != null;
        }
        $this->validator->setRules(
            [
                'year' => [
                    'required',
                    static fn($value) => $rule,
                ],
                'image_1' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'image_2' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
                'image_3' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
            ],
            [
                'year' => [
                    'required' => 'Pilih file terlebih dahulu',
                    1 => 'Tahun yang dipilih telah memiliki timeline'
                ],
                'image_1' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
                'image_2' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
                'image_3' => [
                    'required' => 'Pilih file terlebih dahulu',
                    'is_image' => 'Pilih file berupa image',
                    'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
                ],
            ]
        );
        if ($this->validator->run([
            'year'   => $this->request->getVar('year'),
            'image_1' => $this->request->getFile('image_1'),
            'image_2' => $this->request->getFile('image_2'),
            'image_3' => $this->request->getFile('image_3'),
        ])) return redirect()->to('')->withInput();

        // 1
        $imageFile1 = $this->request->getFile('image_1');
        if ($imageFile1->getError() == 4) {
            $imageName1 = $this->request->getVar('imageNameLama1');
        } else {
            $imageName1 = time() . '_' . $imageFile1->getRandomName();
            $imageFile1->move('assets/img/timeline', $imageName1);
            unlink('assets/img/timeline/' . $this->request->getVar('imageNameLama1'));
        }
        // 2
        $imageFile2 = $this->request->getFile('image_2');
        if ($imageFile2->getError() == 4) {
            $imageName2 = $this->request->getVar('imageNameLama2');
        } else {
            $imageName2 = time() . '_' . $imageFile2->getRandomName();
            $imageFile2->move('assets/img/timeline', $imageName2);
            unlink('assets/img/timeline/' . $this->request->getVar('imageNameLama2'));
        }
        // 3
        $imageFile3 = $this->request->getFile('image_3');
        if ($imageFile3->getError() == 4) {
            $imageName3 = $this->request->getVar('imageNameLama3');
        } else {
            $imageName3 = time() . '_' . $imageFile3->getRandomName();
            $imageFile3->move('assets/img/timeline', $imageName3);
            unlink('assets/img/timeline/' . $this->request->getVar('imageNameLama3'));
        }





        $this->timeline->save([
            'id' => $id,
            'year' => $this->request->getVar('year'),
            'image_1' => $imageName1,
            'image_2' => $imageName2,
            'image_3' => $imageName3,
        ]);

        return redirect()->to('');
    }

    public function delete($id)
    {
        $timeline = $this->timeline->find($id);
        if ($timeline['image_1'] != 'default.jpg')
            unlink('assets/img/timeline/' . $timeline['image_1']);
        if ($timeline['image_2'] != 'default.jpg')
            unlink('assets/img/timeline/' . $timeline['image_2']);
        if ($timeline['image_3'] != 'default.jpg')
            unlink('assets/img/timeline/' . $timeline['image_3']);

        $this->timeline->delete($id);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('');
    }
}

// if (!$this->validate([
//     // <input type="number" min="1900" max="2099" step="1" placeholder="Pilih tahun timeline" />
//     'year' => [
//         'rules' => 'required',
//         'errors' => [
//             'required' => 'Pilih file terlebih dahulu',
//         ]
//     ],
//     'image_1' => [
//         'rules' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
//         'errors' => [
//             'required' => 'Pilih file terlebih dahulu',
//             'is_image' => 'Pilih file berupa image',
//             'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
//         ]
//     ],
//     'image_2' => [
//         'rules' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
//         'errors' => [
//             'required' => 'Pilih file terlebih dahulu',
//             'is_image' => 'Pilih file berupa image',
//             'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
//         ]
//     ],
//     'image_3' => [
//         'rules' => 'required|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/heic]',
//         'errors' => [
//             'required' => 'Pilih file terlebih dahulu',
//             'is_image' => 'Pilih file berupa image',
//             'mime_in' => 'Pilih file format jpg/jpeg/png/heic',
//         ]
//     ],
// ])) {
//     return redirect()->to('')->withInput();
// }