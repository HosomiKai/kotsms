<?php

namespace Hosomikai\Kotsms\Controllers;

use Hosomikai\Kotsms\Facade\Kotsms;
use Hosomikai\Kotsms\Helper\Faker;
use Hosomikai\Kotsms\Kotsms as SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class KotsmsController
{
    /**
     * faker.
     *
     * @var faker
     */
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Demo 寄送簡訊頁面.
     */
    public function index(): View
    {
        $points = Kotsms::queryUserPoints();

        $content = $this->faker->demoContent();

        return view('Kotsms::demo', compact('content', 'points'));
    }

    /**
     * 寄送簡訊.
     */
    public function send(Request $request): array
    {
        $this->validator($request->all())->validate();

        $response = Kotsms::to($request->get('number'))
                            ->content($request->get('content'))
                            ->send();

        return $response->toArray();
    }

    /**
     * Get a validator for an incoming sms request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'number' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    }
}
