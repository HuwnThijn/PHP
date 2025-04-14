@extends('layouts.pharmacist')

@section('title', 'Chi tiết đơn thuốc')

@section('page-title', 'Chi tiết đơn thuốc #' . $prescription->id_prescription)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn thuốc</h6>
                <div>
                    @if($prescription->status == 'pending')
                        <span class="badge badge-warning">Chờ xử lý</span>
                    @elseif($prescription->status == 'completed')
                        <span class="badge badge-success">Đã xử lý</span>
                    @else
                        <span class="badge badge-secondary">{{ $prescription->status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                {{-- Ẩn thông báo lỗi SQL --}}
                @php
                    // Xóa thông báo lỗi SQLSTATE[01000] từ session
                    if (session()->has('sqlError')) {
                        session()->forget('sqlError');
                    }
                @endphp

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin bệnh nhân</h5>
                        <p><strong>Tên:</strong> {{ $prescription->patient->name }}</p>
                        <p><strong>Email:</strong> {{ $prescription->patient->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $prescription->patient->phone }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $prescription->patient->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Thông tin bác sĩ</h5>
                        <p><strong>Tên:</strong> {{ $prescription->doctor->name }}</p>
                        <p><strong>Email:</strong> {{ $prescription->doctor->email }}</p>
                        <p><strong>Điện thoại:</strong> {{ $prescription->doctor->phone }}</p>
                        <p><strong>Chuyên môn:</strong> {{ $prescription->doctor->specialization }}</p>
                    </div>
                </div>

                <h5 class="font-weight-bold mb-3">Danh sách thuốc</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tên thuốc</th>
                                <th>Liều dùng</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                                <th>Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($prescription->items as $item)
                            <tr>
                                <td>{{ $item->medicine->name }}</td>
                                <td>{{ $item->dosage }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }} VNĐ</td>
                                <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                                @php $total += $item->price * $item->quantity; @endphp
                                <td>
                                    @if($item->medicine->stock_quantity >= $item->quantity)
                                        <span class="badge badge-success text-black">{{ $item->medicine->stock_quantity }}</span>
                                    @else
                                        <span class="badge badge-danger text-black">{{ $item->medicine->stock_quantity }} (Thiếu)</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Tổng cộng:</td>
                                <td colspan="2" class="font-weight-bold">{{ number_format($total) }} VNĐ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4">
                    <h5 class="font-weight-bold">Ghi chú của bác sĩ</h5>
                    <p>{{ $prescription->notes ?? 'Không có ghi chú' }}</p>
                </div>

                @if($prescription->status == 'completed')
                <div class="mt-4">
                    <h5 class="font-weight-bold">Thông tin xử lý</h5>
                    <p><strong>Người xử lý:</strong> {{ $prescription->processedBy->name ?? 'N/A' }}</p>
                    <p><strong>Thời gian xử lý:</strong> {{ $prescription->processed_at ? $prescription->processed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p><strong>Phương thức thanh toán:</strong> 
                        @if($prescription->payment_method == 'cash')
                            <span class="badge badge-success text-black">Tiền mặt</span>
                        @elseif($prescription->payment_method == 'card')
                            <span class="badge badge-info text-black">Thẻ</span>
                        @elseif($prescription->payment_method == 'transfer')
                            <span class="badge badge-primary text-black">Chuyển khoản</span>
                        @else
                            <span class="badge badge-secondary text-black">{{ $prescription->payment_method ?? 'N/A' }}</span>
                        @endif
                    </p>
                    @if($prescription->payment_method == 'card' && $prescription->payment_id)
                    <p><strong>Mã giao dịch:</strong> {{ $prescription->payment_id }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($prescription->status == 'pending')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Xử lý đơn thuốc</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pharmacist.prescriptions.process', $prescription->id_prescription) }}" method="POST" id="processForm">
                    @csrf
                    <div class="form-group">
                        <label for="payment_method">Phương thức thanh toán</label>
                        <select class="form-control" id="payment_method" name="payment_method" required onchange="togglePaymentMethod()">
                            <option value="cash">Tiền mặt</option>
                            <option value="card">Thẻ</option>
                            <option value="transfer">Chuyển khoản</option>
                        </select>
                    </div>
                    
                    <div id="cardPaymentSection" style="display: none;">
                        <div class="form-group">
                            <label for="card-element">Thông tin thẻ</label>
                            <div id="card-element" class="form-control" style="height: 40px; padding-top: 10px;">
                                <!-- Stripe Element sẽ được chèn vào đây -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>
                    </div>
                    
                    <button type="button" id="submitButton" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Xác nhận và xuất thuốc
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn thuốc</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã đơn thuốc:</strong> #{{ $prescription->id_prescription }}</p>
                        <p><strong>Ngày tạo:</strong> {{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Trạng thái:</strong> 
                            @if($prescription->status == 'pending')
                                <span class="badge badge-warning">Chờ xử lý</span>
                            @elseif($prescription->status == 'completed')
                                <span class="badge badge-success">Đã xử lý</span>
                            @endif
                        </p>
                        <p><strong>Tổng tiền:</strong> 
                            @php
                                // Tính tổng tiền từ các mục thuốc
                                $totalAmount = 0;
                                foreach ($prescription->items as $item) {
                                    $totalAmount += $item->price * $item->quantity;
                                }
                            @endphp
                            {{ number_format($totalAmount) }} VNĐ
                        </p>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('pharmacist.prescriptions.pending') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    
                    @if($prescription->status == 'completed')
                    <a href="{{ route('pharmacist.prescriptions.print', $prescription->id_prescription) }}" class="btn btn-primary btn-sm" target="_blank">
                        <i class="fas fa-print"></i> In đơn thuốc
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @if($prescription->status == 'pending')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Khởi tạo Stripe.js với Public Key
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();
        
        // Tạo card Element và thêm vào form
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
        
        // Xử lý lỗi khi nhập thông tin thẻ
        cardElement.on('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.textContent = error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        // Hiển thị/ẩn phần thanh toán thẻ dựa trên phương thức được chọn
        function togglePaymentMethod() {
            const paymentMethod = document.getElementById('payment_method').value;
            const cardPaymentSection = document.getElementById('cardPaymentSection');
            
            if (paymentMethod === 'card') {
                cardPaymentSection.style.display = 'block';
            } else {
                cardPaymentSection.style.display = 'none';
            }
        }
        
        // Xử lý khi nhấn nút thanh toán
        document.getElementById('submitButton').addEventListener('click', async function(event) {
            event.preventDefault();
            
            const paymentMethod = document.getElementById('payment_method').value;
            const form = document.getElementById('processForm');
            
            if (paymentMethod === 'card') {
                // Hiển thị loading
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                
                try {
                    // Thêm đoạn log để debug
                    console.log('Đang gửi yêu cầu thanh toán đến:', '/pharmacist/prescriptions/{{ $prescription->id_prescription }}/payment/intent');
                    
                    const response = await fetch('/pharmacist/prescriptions/{{ $prescription->id_prescription }}/payment/intent', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    // Thêm đoạn log để debug response
                    console.log('Nhận phản hồi:', response);
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Lỗi từ server:', errorText);
                        throw new Error('Không thể kết nối tới máy chủ thanh toán: ' + errorText);
                    }
                    
                    const data = await response.json();
                    console.log('Dữ liệu thanh toán:', data);
                    
                    // Hiển thị thông tin thanh toán USD nếu có
                    if (data.amount_usd) {
                        document.getElementById('card-errors').innerHTML = `<div class="alert alert-info mb-2">Thanh toán ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(data.amount_vnd)} ≈ ${new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(data.amount_usd/100)}</div>`;
                    }
                    
                    // Xác nhận thanh toán với Stripe
                    const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
                        payment_method: {
                            card: cardElement
                        }
                    });
                    
                    if (error) {
                        // Hiển thị lỗi từ Stripe
                        console.error('Stripe error:', error);
                        document.getElementById('card-errors').textContent = error.message || 'Lỗi thanh toán thẻ';
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-check"></i> Xác nhận và xuất thuốc';
                    } else if (paymentIntent.status === 'succeeded') {
                        // Thêm ID thanh toán vào form và submit
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripe_payment_id');
                        hiddenInput.setAttribute('value', paymentIntent.id);
                        form.appendChild(hiddenInput);
                        
                        form.submit();
                    }
                } catch (error) {
                    console.error('Lỗi xử lý thanh toán:', error);
                    document.getElementById('card-errors').innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-check"></i> Xác nhận và xuất thuốc';
                }
            } else {
                // Nếu không phải thanh toán bằng thẻ, submit form bình thường
                form.submit();
            }
        });
        
        // Khởi tạo hiển thị khi tải trang
        togglePaymentMethod();
    </script>
    @endif
@endsection 