<?php

namespace Modules\Franchise\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Modules\Franchise\Events\TemplateUpdated;
use Modules\Franchise\Models\FranchiseTemplate;
use Modules\Franchise\Services\MarginGuard;
use Modules\Franchise\Services\MarginViolationException;

class TemplateController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'template_id' => 'required|string',
            'changes' => 'required|array',
        ]);

        $template = FranchiseTemplate::findOrFail($data['template_id']);

        try {
            app(MarginGuard::class)->validate($data['changes']);
        } catch (MarginViolationException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 409);
        }

        $template->data = array_merge($template->data ?? [], $data['changes']);
        $template->version += 1;
        $template->status = 'Synced';
        $template->save();

        Event::dispatch(new TemplateUpdated($template->id, $data['changes']));

        return response()->json(['success' => true]);
    }
}
