<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileSearchRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilesController extends Controller
{
    private ProfileRepository $profileRepository;
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->middleware('auth', ['except' => ['show']]);
        $this->profileRepository = $profileRepository;
    }

    public function index(): View
    {
        $title = 'Find Friends';
        list($users, $friend_requests) = $this->profileRepository->index();

        return view('profiles.index', compact('users', 'friend_requests', 'title'));
    }

    public function show(User $user): View
    {
        list($postCount, $followersCount, $followingCount, $follows, $friendship) = $this->profileRepository->show($user);

        return view('profiles.show', compact(
            'user', 'follows',
            'postCount', 'followersCount',  'followingCount',
            'friendship'
        ));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(User $user, ProfileUpdateRequest $request): RedirectResponse
    {
        $this->authorize('update', $user->profile);
        $this->profileRepository->update($user, $request->validated());

        return redirect()->route('profile.show', ['user' => $user->id]);
    }

    /**
     * @throws Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('register');
    }

    public function find(ProfileSearchRequest $request): View
    {
        $title = 'Search result';
        $users = User::where('name', 'like', '%'. $request->get('username') .'%')->get();

        return view('profiles.index', compact('users', 'title'));
    }
}
