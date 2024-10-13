<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUserIdException;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Fractal\Transformers\UserTransformer;
use App\Services\ImageService;
use App\Services\Responder;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    protected ImageService $imageService;
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param ImageService $imageService
     * @param UserService $userService
     */
    public function __construct(ImageService $imageService, UserService $userService)
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
    }

    /**
     * Retrieve a paginated list of users.
     */
    public function index(UserRequest $request, Responder $responder)
    {
        $count = $request->input('count', 5);
        $usersQuery = User::with('position')->orderBy('id');

        return response()
            ->json($responder($usersQuery, new UserTransformer())
                ->paginate($count)
                ->sendCustomUsers()
                ->toArray());
    }

    /**
     * Retrieve a user by ID.
     * @throws InvalidUserIdException
     */
    public function show($id, Responder $responder)
    {
        // Throw custom exception if ID is not valid
        if (!is_numeric($id) || intval($id) < 1) {
            throw new InvalidUserIdException();
        }

        // Find the user by ID, the validation already ensures the ID is valid
        $userQuery = User::with('position');

        // Check if the user exists
        if (!$userQuery->find($id)) {
            throw new NotFoundHttpException();
        }

        // Transform the user data using Fractal and return the response
        $fractalResponse = $responder($userQuery, new UserTransformer())->sendModelByFind($id)->toArray();

        return response()->success(['message' => 'User retrieved successfully', 'user' => $fractalResponse], 200);
    }

    /**
     * Register a new user.
     */
    public function store(UserRequest $request)
    {
        // Process and save the photo
        $photoPath = null;
        if ($photo = $request->file('photo')) {
            $photoPath = $this->imageService->processAndOptimizePhoto($photo);
        }

        $user = $this->userService->createUser($request->all(), $photoPath);

        // Remove the token after user registration
        $this->userService->removeToken($request);

        return response()->success(['message' => 'New user successfully registered', 'user_id' => $user->id], 201);
    }
}
