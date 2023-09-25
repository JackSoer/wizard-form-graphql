<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DeleteMemberRequest;
use App\Http\Requests\V1\StoreMemberRequest;
use App\Http\Requests\V1\UpdateMemberRequest;
use App\Http\Resources\V1\MemberCollection;
use App\Http\Resources\V1\MemberResource;
use App\Models\Member;
use App\Utils\UploadController;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new MemberCollection(Member::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        $filePath = UploadController::uploadFile('photo');
        $requestData = $request->all();
        $requestData['photo'] = $filePath;

        return new MemberResource(Member::create($requestData));
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return new MemberResource($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        $filePath = UploadController::uploadFile('photo');

        $requestData = $request->all();

        if ($filePath) {
            $requestData['photo'] = $filePath;
        }

        $oldPhoto = $member->photo;
        $photoIsNull = key_exists('photo', $requestData) && is_null($requestData['photo']);

        if ($filePath && $oldPhoto || $photoIsNull && $oldPhoto) {
            UploadController::deleteFile($oldPhoto);
        }

        $member->update($requestData);

        return new MemberResource($member);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member, DeleteMemberRequest $request)
    {
        $member->delete();

        $oldPhoto = $member->photo;

        if ($oldPhoto) {
            UploadController::deleteFile($oldPhoto);
        }

        return new MemberResource($member);
    }
}
