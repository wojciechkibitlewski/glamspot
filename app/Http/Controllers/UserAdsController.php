<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Models\Ad;
use App\Models\AdJob;
use App\Models\AdMachine;
use App\Models\AdPhoto;
use App\Models\AdTraining;
use App\Models\AdTrainingDate;
use App\Models\AdTrainingSpecialization;
use App\Models\Category;
use App\Models\City;
use App\Models\Industry;
use App\Models\Region;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserAdsController extends Controller
{
    /////////////////////
    //
    // Funkcje pomocnicze 
    //
    /////////////////////
    protected function ensureCityExists(?string $cityName, ?string $regionName): void
    {
        $name = trim((string) ($cityName ?? ''));
        $regionText = trim((string) ($regionName ?? ''));
        if ($name === '' || $regionText === '') {
            return;
        }

        $region = Region::query()->where('name', $regionText)->first();
        if (! $region) {
            return;
        }

        $slug = Str::slug($name);
        $exists = City::query()
            ->where('region_id', $region->id)
            ->where('slug', $slug)
            ->exists();
        if (! $exists) {
            City::create([
                'region_id' => $region->id,
                'name' => $name,
                'slug' => $slug,
            ]);
        }
    }

    /////////////////////
    //
    // CRUD
    //
    /////////////////////

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $ads = Ad::query()
            ->where('user_id', $user->id)
            ->with(['category', 'photos'])
            ->latest('id')
            ->paginate(10);

        return view('sites/user-ads/index', [
            'ads' => $ads,
        ]);
    }

    public function show(string $code, string $slug)
    {
        /** @var User $user */
        $user = Auth::user();

        $ad = Ad::query()
            ->with(['category', 'photos', 'job.specializations', 'job.subcategory', 'training', 'training.dates', 'machines', 'user.firm'])
            ->where('user_id', $user->id)
            ->where('code', $code)
            ->firstOrFail();

        $expected = Str::slug((string) $ad->title);
        if ($slug !== $expected) {
            return redirect()->route('user-ads.show', [$ad->code, $expected]);
        }

        return view('sites/user-ads/show', [
            'ad' => $ad,
        ]);
    }


}
