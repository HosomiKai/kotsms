<?php

namespace Hosomikai\Kotsms\Controllers;

use Hosomikai\Kotsms\Facade\Kotsms;
use Hosomikai\Kotsms\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class KotsmsController
{
    /**
     * Helper.
     *
     * @var Helper
     */
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Demo 寄送簡訊頁面.
     */
    public function index(): View
    {
        $content = $this->helper->demoContent();

        return view('Kotsms::demo', compact('content'));
    }

    /**
     * 寄送簡訊.
     */
    public function send(Request $request): View
    {
        $this->validator($request->all())->validate();

        $kotsms = Kotsms::to($request->get('number'))
            ->content($request->get('content'))
            ->send()
            ->getStatus();

        return view('Kotsms::demo', compact('kotsms'));
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'number' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    }
}
