<?php

namespace App\Http\Controllers\Api\Todo;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\Todo;
use App\Models\UserToken;
use App\Http\Traits\RespondTrait;

class TodoController extends ApiController
{
    use RespondTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $todos = Todo::whereHas('user_token', function ($query) use ($request) {
            $query->where('uuid', $request->cookie('uuid'));
        })
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'message' => 'Success!',
            'todos' => $todos
        ], 200);
    }

    /**
     * Store new todo
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->respondNotValidated(
                $validator->errors()->first(),
                $validator->errors()->all()
            );
        }

        try {
            DB::beginTransaction();

            $userToken = new UserToken();
            $userToken->uuid = $request->cookie('uuid');
            $userToken->save();

            $todo = (new Todo)->fill(request()->only(Todo::getFillableAttributes()));
            $todo->id = $request->id;
            $todo->user_token_id = $userToken->id;
            $todo->save();

            DB::commit();

            return response()->json([
                'message' => 'Success!',
                'todo' => $todo
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Save all todos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        // $uuid = explode("|", \Crypt::decryptString($request->cookie('uuid')));
        $ids = [];
        $todos = $request->get('todos');
        $uuid = UserToken::where('uuid', $request->cookie('uuid'))->first();

        // dd(strpos($uuid, "|"), typeOf($uuid));
        // dd($request->get('uuid'));
        // dd($uuid, \Crypt::decryptString($request->cookie('uuid')));
        // dd($request->cookie('uuid'));

        $todos = array_map(function ($t) use ($uuid) {
            $ids[] = $t['id'];
            $todo['completed'] = $t['completed'] ?? 0;
            $todo['title'] = $t['title'] ?? "";
            $todo['user_token_id'] = $uuid['id'];
            $todo['id'] = $t['id'];

            return $todo;
        }, $todos);

        try {
            DB::beginTransaction();

            Todo::whereHas('user_token', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })->whereNotIn('id', $ids)
                ->delete();

            Todo::whereHas('user_token', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            })->upsert($todos, ['id'], ['title', 'completed']);

            DB::commit();

            return response()->json([
                'message' => 'Success!',
                'todos' => $todos
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Delete all todos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clear(Request $request)
    {
        try {
            DB::beginTransaction();

            Todo::whereHas('user_token', function ($query) use ($request) {
                $query->where('uuid', $request->cookie('uuid'));
            })->delete();   

            DB::commit();

            return response()->json([
                'message' => 'Success!',
                'todo' => null
            ], 200);
            
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->respondNotValidated(
                'Failed!',
                $exception->getMessage()
            );
        }
    }
}
