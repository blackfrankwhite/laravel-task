<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Repositories\TokenRepository;

class TokenController extends Controller
{
    /**
     * @var TokenRepository
     */
    protected $tokenRepository;

    /**
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function create(Request $request)
    {
        return response()->json(['token' =>  $this->tokenRepository->createToken()], 200);
    }

    public function delete(Request $request)
    {
        $data = $request->get('data');

        return response()->json($this->tokenRepository->deleteToken($data['token']), 200);
    }
}