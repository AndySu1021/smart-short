<?php

namespace Database\Factories;

use App\Models\UrlMapping;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlMappingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UrlMapping::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $url = $this->faker->url();

        return [
            'code' => md5($url),
            'long_url' => $url,
            'short_url' => 'ABC',
            'create_time' => Carbon::now()->toDateTimeString(),
        ];
    }
}
