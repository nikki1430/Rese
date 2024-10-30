<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{

    public function userShow()
    {
        $users = User::with('roles')->Paginate(10);
        $roles = Role::all();

        return view('admin.user', compact('users', 'roles'));
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->username;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole('writer');

        return view('admin.done');
    }

    public function search(Request $request)
    {
        $roleId = $request->input('role_id');
        $query = User::with('roles');

        if ($roleId === 'all') {
            $users = $query->get();
        } elseif ($roleId === 'user') {
            $users = $query->doesntHave('roles')->get();
        } else {
            $users = $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            })->get();
        }

        return response()->json($users);
    }

    public function importIndex()
    {
        return view('admin.csv_import');
    }

    public function csvImport(Request $request)
    {
        $csvFile = $request->file('csvFile');
        $csv = Reader::createFromPath($csvFile->getRealPath(), 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();
        $errors = [];
        $rowNumber = 2;

        foreach ($records as $record) {
            $customMsgs = [
                '店舗名.required' => '店舗名は50文字以内で入力してください',
                '地域.required' => '地域は「東京都」「大阪府」「福岡県」のいずれかを入力してください',
                '地域.exists' => '地域は「東京都」「大阪府」「福岡県」のいずれかを入力してください',
                'ジャンル.required' => 'ジャンルは「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれかを入力してください',
                'ジャンル.exists' => 'ジャンルは「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれかを入力してください',
                '店舗概要.required' => '店舗概要は400文字以内で入力してください',
                '画像URL.url' => '画像URLは、URL形式で「jpeg」「png」のみアップロード可能です',
                '画像URL.regex' => '画像URLは、URL形式で「jpeg」「png」のみアップロード可能です'
            ];

            $validator = Validator::make($record, [
                '店舗名' => 'required|max:50',
                '地域' => 'required|exists:areas,name',
                'ジャンル' => 'required|exists:genres,name',
                '店舗概要' => 'required|max:400',
                '画像URL' => ['required', 'url', 'regex:/\.(jpg|png)$/i'],
            ], $customMsgs);

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $message) {
                    foreach ($message as $specificError) {
                        $errors[] = "行{$rowNumber}: {$specificError}";
                    }
                }
                $rowNumber++;
                continue;
            }

            $area = Area::where('name', $record['地域'])->first();
            $genre = Genre::where('name', $record['ジャンル'])->first();

            Shop::create([
                'name' => $record['店舗名'],
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'outline' => $record['店舗概要'],
                'image_url' => $record['画像URL'],
            ]);

            $rowNumber++;
        }

        if (!empty($errors)) {
            return back()->with('errors', $errors);
        }

        return back()->with('success', 'CSVファイルのインポートが完了しました');
    }
}
