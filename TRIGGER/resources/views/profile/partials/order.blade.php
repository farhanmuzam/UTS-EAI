<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Your history orders
        </h2>

        <div class="mt-1 text-sm text-gray-600">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Product name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $data)
                        <tr class="bg-white border-b">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $data['product']['name'] }}
                            </th>
                            <td class="px-6 py-4">
                                Rp. {{ $data['product']['price'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($data['created_at'])->format('d F Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </header>
</section>
