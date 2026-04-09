<x-filament-panels::page>

    {{ $this->infolist }}

    @php
        $properties = $this->record->properties ?? [];

        $attributes = $properties['attributes'] ?? [];
        $old = $properties['old'] ?? [];

        // Flatten nested arrays into dot.notation => value
        $flatten = function ($array, $prefix = '') use (&$flatten) {
            $result = [];
            foreach ($array as $k => $v) {
                $key = $prefix === '' ? $k : $prefix . '.' . $k;
                if (is_array($v)) {
                    $result = array_merge($result, $flatten($v, $key));
                } else {
                    $result[$key] = $v;
                }
            }
            return $result;
        };

        $flatNew = $flatten($attributes);
        $flatOld = $flatten($old);

        $allKeys = array_unique(array_merge(array_keys($flatNew), array_keys($flatOld)));
        sort($allKeys);
    @endphp

    <div class="mt-6">
        <h3 class="text-lg font-semibold">Properties comparison (attributes vs old)</h3>

        <div class="mt-2 flex gap-2 items-center">
            <label class="inline-flex items-center gap-2">
                <input id="showChangedOnly" type="checkbox" class="rounded border-gray-300" />
                <span class="text-sm">Show only changed rows</span>
            </label>

            <div class="ml-auto flex gap-2">
                <button type="button" id="copyOld" class="px-2 py-1 bg-gray-100 rounded text-sm">Copy Old JSON</button>
                <button type="button" id="copyNew" class="px-2 py-1 bg-gray-100 rounded text-sm">Copy New JSON</button>
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

        <div class="overflow-auto mt-3">
            <table class="w-full border-collapse table-auto text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left border">Key</th>
                        <th class="p-2 text-left border">Old</th>
                        <th class="p-2 text-left border">New</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allKeys as $key)
                        @php
                            $left = array_key_exists($key, $flatOld) ? $flatOld[$key] : null;
                            $right = array_key_exists($key, $flatNew) ? $flatNew[$key] : null;
                            $changed = ($left !== $right);

                            // Nicely format booleans/null
                            $fmt = function($v) {
                                if ($v === null) return '<small class="text-gray-500">(null)</small>';
                                if (is_bool($v)) return $v ? 'true' : 'false';
                                if (is_array($v) || is_object($v)) {
                                    return '<pre class="text-xs p-1 bg-white">' . e(json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
                                }
                                return e((string) $v);
                            };
                        @endphp
                        <tr data-changed="{{ $changed ? 1 : 0 }}" class="{{ $changed ? 'bg-yellow-50' : '' }}">
                            <td class="p-2 align-top border">{{ $key }}</td>
                            <td class="p-2 align-top border" style="white-space:pre-wrap;max-width:45%;">{!! $fmt($left) !!}</td>
                            <td class="p-2 align-top border" style="white-space:pre-wrap;max-width:45%;">{!! $fmt($right) !!}</td>
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

            checkbox.addEventListener('change', updateRows);

            copyOld.addEventListener('click', async function(){
                const text = document.getElementById('fullOldJson').textContent || '';
                await navigator.clipboard.writeText(text);
                this.textContent = 'Copied';
                setTimeout(()=> this.textContent = 'Copy Old JSON', 1500);
            });

            copyNew.addEventListener('click', async function(){
                const text = document.getElementById('fullNewJson').textContent || '';
                await navigator.clipboard.writeText(text);
                this.textContent = 'Copied';
                setTimeout(()=> this.textContent = 'Copy New JSON', 1500);
            });
        })();
    </script>

</x-filament-panels::page>

