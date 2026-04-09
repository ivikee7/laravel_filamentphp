<x-filament-panels::page>

    {{ $this->infolist }}

    @php
        $rows = $this->comparisonRows();
        $total = count($rows);
        $changedCount = collect($rows)->where('changed', true)->count();
    @endphp

    <div class="mt-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold">Properties comparison</h3>
                <p class="text-sm text-gray-500">Aligned view showing each property key with previous (Old) and current (New) values.</p>
            </div>

            <div class="text-right">
                <div class="text-sm text-gray-600">Total keys: <strong>{{ $total }}</strong></div>
                <div class="text-sm text-yellow-600">Changed: <strong>{{ $changedCount }}</strong></div>
            </div>
        </div>

        <div class="mt-3 flex items-center gap-3">
            <label class="inline-flex items-center gap-2 text-sm">
                <input id="showChangedOnly" type="checkbox" class="rounded border-gray-300" checked />
                <span>Show only changed rows</span>
            </label>

            <div class="ml-auto flex gap-2">
                <button type="button" id="copyOld" class="px-3 py-1 text-sm rounded bg-gray-100">Copy Old JSON</button>
                <button type="button" id="copyNew" class="px-3 py-1 text-sm rounded bg-gray-100">Copy New JSON</button>
            </div>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-4">
            <div>
                <h4 class="font-medium">Full Old (properties[old])</h4>
                <pre id="fullOldJson" class="p-2 bg-gray-50 rounded text-xs overflow-auto" style="max-height:220px">{{ json_encode($old, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            <div>
                <h4 class="font-medium">Full New (properties[attributes])</h4>
                <pre id="fullNewJson" class="p-2 bg-gray-50 rounded text-xs overflow-auto" style="max-height:220px">{{ json_encode($attributes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>

        <div class="overflow-auto mt-4 border rounded bg-white">
            <table class="w-full table-fixed text-sm">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="p-2 text-left border w-1/3">Key</th>
                        <th class="p-2 text-left border w-1/3">Old</th>
                        <th class="p-2 text-left border w-1/3">New</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        @php
                            $fmt = function($v) {
                                if ($v === null) return '<small class="text-gray-400">(null)</small>';
                                if (is_bool($v)) return $v ? '<span class="text-green-600">true</span>' : '<span class="text-red-600">false</span>';
                                if (is_array($v) || is_object($v)) {
                                    return '<pre class="text-xs p-1 bg-gray-50">' . e(json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
                                }
                                return e((string) $v);
                            };
                        @endphp
                        <tr data-changed="{{ $r['changed'] ? 1 : 0 }}" class="{{ $r['changed'] ? 'border-l-4 border-yellow-400 bg-yellow-50' : '' }}">
                            <td class="p-2 align-top border" style="white-space:normal;">{{ $r['key'] }}</td>
                            <td class="p-2 align-top border" style="white-space:pre-wrap;">{!! $fmt($r['old']) !!}</td>
                            <td class="p-2 align-top border" style="white-space:pre-wrap;">{!! $fmt($r['new']) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (function(){
            const checkbox = document.getElementById('showChangedOnly');
            const copyOld = document.getElementById('copyOld');
            const copyNew = document.getElementById('copyNew');

            function updateRows(){
                const showOnly = checkbox.checked;
                document.querySelectorAll('tr[data-changed]').forEach(tr => {
                    const changed = tr.getAttribute('data-changed') === '1';
                    if(showOnly && !changed) tr.style.display = 'none'; else tr.style.display = '';
                });
            }

            // initialize
            updateRows();

            checkbox.addEventListener('change', updateRows);

            copyOld.addEventListener('click', async function(){
                const text = document.getElementById('fullOldJson').textContent || '';
                try{ await navigator.clipboard.writeText(text); this.textContent = 'Copied'; }catch(e){ this.textContent='Copy'; }
                setTimeout(()=> this.textContent = 'Copy Old JSON', 1500);
            });

            copyNew.addEventListener('click', async function(){
                const text = document.getElementById('fullNewJson').textContent || '';
                try{ await navigator.clipboard.writeText(text); this.textContent = 'Copied'; }catch(e){ this.textContent='Copy'; }
                setTimeout(()=> this.textContent = 'Copy New JSON', 1500);
            });
        })();
    </script>

</x-filament-panels::page>

