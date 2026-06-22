<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\StoreServiceRequestRequest;
use App\Http\Requests\ServiceRequest\UpdateUserServiceRequestRequest;
use App\Models\Notification;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // ← مضاف

class ServiceRequestController extends Controller
{
    private function resolveActorIds(): array
    {
        $userId        = null;
        $associationId = null;

        if (Auth::check()) {
            $pk = Auth::user()?->getAttribute('id');
            if (is_numeric($pk)) {
                $userId = (int) $pk;
            }
        }

        $assocSession = session('association');
        if (is_array($assocSession) && isset($assocSession['id']) && is_numeric($assocSession['id'])) {
            $associationId = (int) $assocSession['id'];
        }

        return [$userId, $associationId];
    }

    // Normalize DB status → UI status for the user-facing side
    private function normalizeStatus(string $status): string
    {
        return match ($status) {
            'in_progress' => 'review',
            'completed'   => 'approved',
            default       => $status, // pending, rejected
        };
    }

    public function index()
    {
        [$userId, $associationId] = $this->resolveActorIds();

        $query = ServiceRequest::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($associationId) {
            $query->where('association_id', $associationId);
        } else {
            Log::channel('api')->warning('محاولة جلب طلبات بدون هوية', [
                'ip' => request()->ip(),
            ]);
            return response()->json([]);
        }

        $typeLabels = [
            'units'       => 'بناء وحدات/أنظمة',
            'training'    => 'تدريب المتطوعين',
            'initiatives' => 'تنسيق المبادرات',
            'consulting'  => 'استشارات متخصصة',
            'other'       => 'طلب آخر',
        ];

        $requests = $query->orderByDesc('id')->get()->map(function ($req) use ($typeLabels) {
            return [
                'id'        => $req->id,
                'type'      => $req->service_type,
                'typeLabel' => $typeLabels[$req->service_type] ?? 'طلب خدمة',
                'title'     => $req->title,
                'details'   => $req->details,
                'date'      => $req->created_at ? $req->created_at->translatedFormat('d M Y') : null,
                'budget'    => $req->budget,
                'status'    => $this->normalizeStatus($req->status),
            ];
        });

        return response()->json($requests);
    }

    public function store(StoreServiceRequestRequest $request)
    {
        try {
            $validated = $request->validated();

            [$userId, $associationId] = $this->resolveActorIds();

            if (!$userId && !$associationId) {
                Log::channel('api')->warning('محاولة إنشاء طلب خدمة بدون صلاحية', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 401);
            }

            $sr = ServiceRequest::create([
                'user_id'        => $userId,
                'association_id' => $associationId,
                'service_type'   => $validated['service_type'],
                'title'          => $validated['title'],
                'details'        => $validated['details'],
                'budget'         => $validated['budget'] ?? 0,
                'preferred_date' => $validated['preferred_date'] ?? null,
                'status'         => 'pending',
            ]);

            Log::channel('api')->info('تم إنشاء طلب خدمة جديد', [
                'service_request_id' => $sr->id,
                'user_id'            => $userId,
                'association_id'     => $associationId,
                'service_type'       => $sr->service_type,
            ]);

            // Notify the first admin
            $adminId = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->value('id');
            if ($adminId) {
                $requesterLabel = $associationId
                    ? (session('association.name') ?? 'جمعية')
                    : (Auth::user()?->full_name ?? 'مستخدم');

                Notification::create([
                    'user_id'      => $adminId,
                    'title'        => 'طلب خدمة جديد',
                    'body'         => "وصل طلب خدمة جديد من {$requesterLabel}: {$sr->title}",
                    'type'         => 'service_request_created',
                    'related_id'   => $sr->id,
                    'related_type' => ServiceRequest::class,
                ]);
            } else {
                Log::channel('api')->warning('لم يتم إشعار الأدمن — لا يوجد أدمن في النظام', [
                    'service_request_id' => $sr->id,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'تم إرسال طلبك بنجاح']);
        } catch (\Exception $e) {
            Log::channel('api')->error('فشل إنشاء طلب خدمة', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return response()->json(['success' => false, 'message' => 'حدث خطأ، حاول مرة أخرى'], 500);
        }
    }

    public function update(UpdateUserServiceRequestRequest $request, $id)
    {
        try {
            [$userId, $associationId] = $this->resolveActorIds();

            $query = ServiceRequest::where('id', $id)->where('status', 'pending'); // only editable when pending
            if ($userId) {
                $query->where('user_id', $userId);
            } elseif ($associationId) {
                $query->where('association_id', $associationId);
            } else {
                Log::channel('api')->warning('محاولة تعديل طلب خدمة بدون صلاحية', [
                    'request_id' => $id,
                    'ip'         => $request->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 401);
            }

            $sr = $query->firstOrFail();

            $validated = $request->validated();

            $sr->update([
                'service_type'   => $validated['service_type'],
                'title'          => $validated['title'],
                'details'        => $validated['details'],
                'budget'         => $validated['budget'] ?? 0,
                'preferred_date' => $validated['preferred_date'] ?? null,
            ]);

            Log::channel('api')->info('تم تعديل طلب خدمة', [
                'service_request_id' => $sr->id,
                'user_id'            => $userId,
                'association_id'     => $associationId,
            ]);

            return response()->json(['success' => true, 'message' => 'تم تعديل الطلب بنجاح']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            Log::channel('api')->warning('محاولة تعديل طلب خدمة غير موجود', [
                'request_id' => $id,
                'user_id'    => auth()->id(),
            ]);
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود أو لا يمكن تعديله'], 404);
        } catch (\Exception $e) {
            Log::channel('api')->error('فشل تعديل طلب خدمة', [
                'request_id' => $id,
                'user_id'    => auth()->id(),
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
            ]);
            return response()->json(['success' => false, 'message' => 'حدث خطأ، حاول مرة أخرى'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            [$userId, $associationId] = $this->resolveActorIds();

            $query = ServiceRequest::where('id', $id);
            if ($userId) {
                $query->where('user_id', $userId);
            } elseif ($associationId) {
                $query->where('association_id', $associationId);
            } else {
                Log::channel('api')->warning('محاولة حذف طلب خدمة بدون صلاحية', [
                    'request_id' => $id,
                    'ip'         => request()->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 401);
            }

            $query->firstOrFail()->delete();

            Log::channel('api')->info('تم حذف طلب خدمة', [
                'service_request_id' => $id,
                'user_id'            => $userId,
                'association_id'     => $associationId,
            ]);

            return response()->json(['success' => true, 'message' => 'تم حذف الطلب']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            Log::channel('api')->warning('محاولة حذف طلب خدمة غير موجود', [
                'request_id' => $id,
                'user_id'    => auth()->id(),
            ]);
            return response()->json(['success' => false, 'message' => 'الطلب غير موجود'], 404);
        } catch (\Exception $e) {
            Log::channel('api')->error('فشل حذف طلب خدمة', [
                'request_id' => $id,
                'user_id'    => auth()->id(),
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
            ]);
            return response()->json(['success' => false, 'message' => 'حدث خطأ، حاول مرة أخرى'], 500);
        }
    }
}
