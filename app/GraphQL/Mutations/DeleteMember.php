<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Member;

final readonly class DeleteMember
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $member = Member::findOrFail($args['id']);

        $member->delete();

        return true;
    }
}
