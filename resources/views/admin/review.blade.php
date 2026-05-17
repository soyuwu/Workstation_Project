@extends('admin.admin_master')

@section('page-title', 'Đánh Giá & Phản Hồi')

@section('content')
<div id="section-review">
    <div class="section-header">
        <div>
            <h1 class="page-title">Đánh Giá & Phản Hồi</h1>
        </div>
    </div>

    <div style="display: grid; gap: 20px;">
        @forelse($reviews ?? [] as $review)
            <div class="card" style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #4b5563; font-size: 18px;">
                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 16px;">{{ $review->user->name ?? 'Khách vãng lai' }}</div>
                            <div class="text-sm text-muted">Đã đặt: {{ $review->workspace->name ?? 'Không gian' }} (Mã HĐ: {{ $review->booking->booking_code ?? 'N/A' }})</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: #f59e0b; font-size: 18px;">
                            @for($i=1; $i<=5; $i++)
                                <i class="{{ $i <= $review->rating ? 'ph-fill' : 'ph' }} ph-star"></i>
                            @endfor
                        </div>
                        <div class="text-sm text-muted">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</div>
                    </div>
                </div>

                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 15px; border-left: 3px solid #e5e7eb;">
                    "{{ $review->comment ?? 'Đánh giá này không có nhận xét.' }}"
                </div>

                @if($review->adminReplies && $review->adminReplies->count() > 0)
                    <div style="margin-left: 40px; margin-bottom: 15px; border-left: 3px solid #3b82f6; padding-left: 15px;">
                        @foreach($review->adminReplies as $reply)
                            <div style="font-weight: 600; color: #3b82f6; margin-bottom: 5px;">Quản trị viên đã trả lời:</div>
                            <div style="color: #4b5563; white-space: pre-wrap;">{{ $reply->reply_text }}</div>
                            <div class="text-sm text-muted" style="margin-top: 5px;">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</div>
                        @endforeach
                    </div>
                @endif

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button class="btn btn-outline btn-sm" onclick="openReplyModal({{ $review->id }})">
                        <i class="ph-bold ph-chat-text"></i> Gửi Phản Hồi
                    </button>
                </div>
            </div>
        @empty
            <div class="card text-center" style="padding: 50px; color: #6b7280; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <i class="ph-bold ph-star" style="font-size: 64px; margin-bottom: 15px; color: #d1d5db;"></i>
                <p style="font-size: 16px;">Hệ thống chưa ghi nhận đánh giá nào từ khách hàng.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="replyModal" class="schedule-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="modal-content" style="background: #ffffff; color: #111827; width: 500px; max-width: 95%; border-radius: 16px; padding: 30px; position: relative;">
        <i class="ph-bold ph-x close-modal" onclick="closeReplyModal()" style="position: absolute; top: 20px; right: 20px; font-size: 24px; cursor: pointer; color: #6b7280;"></i>
        
        <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px;">Trả lời đánh giá</h2>
        
        <form id="replyForm">
            <input type="hidden" id="review_id" name="id">
            <div class="form-group" style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 500; margin-bottom: 5px;">Nội dung phản hồi (*)</label>
                <textarea id="reply_text" name="reply_text" class="input-field" style="min-height: 120px; width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: inherit; font-size: 14px;" required placeholder="Nhập câu trả lời của bạn gửi đến khách hàng..."></textarea>
            </div>
            
            <div style="margin-top: 25px; text-align: right;">
                <button type="button" class="btn btn-outline" onclick="closeReplyModal()" style="margin-right: 10px;">Hủy</button>
                <button type="submit" class="btn btn-primary">Gửi Phản Hồi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReplyModal(reviewId) {
        document.getElementById('replyForm').reset();
        document.getElementById('review_id').value = reviewId;
        document.getElementById('replyModal').style.display = 'flex';
    }

    function closeReplyModal() {
        document.getElementById('replyModal').style.display = 'none';
    }

    document.getElementById('replyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('review_id').value;
        const replyText = document.getElementById('reply_text').value;
        const url = `{{ url('admin/review') }}/${id}/reply`;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reply_text: replyText })
            });

            const data = await response.json();
            if (response.ok && data.success) {
                location.reload();
            } else {
                alert('Lưu không thành công!');
            }
        } catch (err) {
            alert('Lỗi kết nối máy chủ!');
        }
    });
</script>
@endsection
