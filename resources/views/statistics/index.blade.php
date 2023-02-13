<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Statistics') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ["Element", "Plans", 'Fact', { role: "style" } ],
                        @foreach($groups as $group)
                            ["{{ $group->title }}", {{ $group->sumPlans }}, {{ $group->sumChecks }}, "#d1d5db"],

                        @endforeach
                    ]);

                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

                    var options = {
                        title: "Plans for Groups",
                        width: '100%',
                        height: 'auto',
                        backgroundColor: '#1f2937',
                        colors:['#aeb0b4','#696e74'],
                        titleTextStyle: {
                            color: '#d1d5db'
                        },
                        hAxis: {
                            textStyle:{color: '#ffffff'},
                        },
                        vAxis: {
                            textStyle:{color: '#ffffff'},
                        },
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },

                    };
                    var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
                    chart.draw(view, options);
                }
            </script>
            <div id="barchart_values" style="width: 100%; height: 700px;"></div>




            @foreach($groups as $group)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-xl font-semibold">
                        {{ $group->title }}

                        <script type="text/javascript">
                            google.charts.load("current", {packages:["corechart"]});
                            google.charts.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ["Element", "Plans", 'Fact', { role: "style" } ],
                                        @foreach($group->categories as $category)
                                            ["{{ $category->title }}", {{ $category->plans->first()->cash }}, {{  $category->checks->where('created_at', '>', $user->start_date_month)->sum('cash')|round(0)   }}, "#d1d5db"],
                                        @endforeach
                                ]);

                                var view = new google.visualization.DataView(data);
                                view.setColumns([0, 1,
                                    { calc: "stringify",
                                        sourceColumn: 1,
                                        type: "string",
                                        role: "annotation" },
                                    2]);

                                var options = {
                                    title: "Plans for {{ $group->title }}",
                                    width: '100%',
                                    height: 'auto',
                                    backgroundColor: '#1f2937',
                                    colors:['#aeb0b4','#696e74'],
                                    titleTextStyle: {
                                        color: '#d1d5db'
                                    },
                                    hAxis: {
                                        textStyle:{color: '#ffffff'},
                                    },
                                    vAxis: {
                                        textStyle:{color: '#ffffff'},
                                    },
                                    bar: {groupWidth: "95%"},
                                    legend: { position: "none" },

                                };
                                var chart = new google.visualization.BarChart(document.getElementById("barchart_values{{ $group->id }}"));
                                chart.draw(view, options);
                            }
                        </script>

                        <div id="barchart_values{{ $group->id }}" style="width: 100%; height: 700px;"></div>

                    </div>
                </div>
            @endforeach






            @if(session()->get('error'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3><x-input-error class="mt-2 text-m" :messages="session()->get('error')" /></h3>
                </div>
            @endif

            @if(session()->get('status'))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex justify-between">{{session()->get('status')}}</h3>
                </div>
            @endif



        </div>
    </div>
</x-app-layout>
