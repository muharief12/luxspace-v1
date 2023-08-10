<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaction &raquo; #{{ $transaction->id }} {{ $transaction->name }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            // Ajax Datatable
            var datatable = $('#crudTable').DataTable ({
            ajax : {
                url: '{!! url()->current() !!}'
            },
            columns: [
                {data:'id', name: 'id', width: '5%'},
                {data:'product.name', name: 'product.name'},
                {data:'product.price', name: 'product.price'},
            ]
        })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-5">
                Transaction Detail
            </h2>

            <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-10">
                <div class="p-6 bg-white border-b border-gray-600">
                    <table class="table-auto w-full">
                        <tbody>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Name
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->name }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Email
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->email }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Address
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->address }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Phone
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->phone }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Courier
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->courier }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    payment
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->payment }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    payment URL
                                </th>
                                <td class="border px-6 py-4">
                                    {{ $transaction->payment_url }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Total Price
                                </th>
                                <td class="border px-6 py-4">
                                    Rp {{ number_format($transaction->total_price) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-6 py-4 text-right w-45">
                                    Status
                                </th>
                                <td class="border px-6 py-4">
                                    @if ( $transaction->status === 'PENDING')
                                        <button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-yellow-200 text-yellow-800">{{ $transaction->status }}</button>
                                    @elseif ( $transaction->status === 'SUCCESS' )
                                        <button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-green-200 text-green-800 font-semibold">{{ $transaction->status }}</button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight mb-5">
                Transaction Item
            </h2>
        <div class="shadow overflow-hidden sm-rounded-md">
            <div class="px-4 py-5 sm:p-6">
                <table id="crudTable" class="text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
