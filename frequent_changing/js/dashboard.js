$(function () {
  "use strict";
  feather.replace();
  //BAR CHART
  let purchase = $("#purchase").val();
  let sale = $("#sale").val();
  let waste = $("#waste").val();
  let expense = $("#expense").val();
  let cust_rcv = $("#cust_rcv").val();
  let supp_pay = $("#supp_pay").val();
  let purchase_value = Number($("#purchase_value").val());
  let sale_value = Number($("#sale_value").val());
  let waste_value = Number($("#waste_value").val());
  let expense_value = Number($("#expense_value").val());
  let cust_rcv_value = Number($("#cust_rcv_value").val());
  let supp_pay_value = Number($("#supp_pay_value").val());

  let dinein_count = Number($("#dinein_count").val());
  let take_away_count = Number($("#take_away_count").val());
  let delivery_count = Number($("#delivery_count").val());
  base_url = $("#base_url_").val();

  let bar = new Morris.Bar({
    element: "operational_comparision",
    resize: true,
    data: [
      {
        y: purchase,
        a: purchase_value,
      },
      {
        y: sale,
        a: sale_value,
      },
      {
        y: waste,
        a: waste_value,
      },
      {
        y: expense,
        a: expense_value,
      },
      {
        y: cust_rcv,
        a: cust_rcv_value,
      },
      {
        y: supp_pay,
        a: supp_pay_value,
      },
    ],
    barColors: ["#7367f0", "#7367f0"],
    xkey: "y",
    ykeys: ["a"],
    labels: ["Amount"],
    hideHover: "auto",
  });

  $(
    "#low_stock_ingredients, #top_ten_food_menu, #top_ten_customer, #customer_receivable, #supplier_payable"
  ).slimscroll({
    height: "220px",
  });

  // -------------
  // - PIE CHART -
  // -------------
  // Get context with jQuery - using jQuery's .get() method.
  let pieChartCanvas = $("#pieChart").get(0).getContext("2d");
  let pieChart = new Chart(pieChartCanvas);
  let PieData = [
    {
      value: dinein_count,
      color: "#7367f0",
      highlight: "#7367f0",
      label: "Dine In",
    },
    {
      value: take_away_count,
      color: "#8e67f0",
      highlight: "#8e67f0",
      label: "Take Away",
    },
    {
      value: delivery_count,
      color: "#6776f0",
      highlight: "#6776f0",
      label: "Delivery",
    },
  ];
  let pieOptions = {
    // Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    // String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    // Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    // Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    // Number - Amount of animation steps
    animationSteps: 100,
    // String - Animation easing effect
    animationEasing: "easeOutBounce",
    // Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    // Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    // Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    // String - A legend template
    legendTemplate:
      "<ul class='<%=name.toLowerCase()%>-legend'><% for (let i=0; i<segments.length; i++){%><li><span style='background-color:<%=segments[i].fillColor%>'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    // String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%> Orders",
  };
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  // -----------------
  // - END PIE CHART -
  // -----------------

  //show last 12 months sale comparison
  selectMonth(12);
  $("#operational_coparision_range").on("click", function () {
    $("#operation_comparision_range_fields").show();
  });
  $("#operation_comparision_cancel").on("click", function () {
    $("#operation_comparision_range_fields").hide();
  });

  function selectMonth(value) {
    let csrf_name_ = $("#csrf_name_").val();
    let csrf_value_ = $("#csrf_value_").val();
    $.ajax({
      url: base_url + "Dashboard/comparison_sale_report_ajax_get",
      type: "get",
      datatype: "json",
      data: {
        months: value,
        csrf_name_: csrf_value_,
      },
      success: function (response) {
        let json = $.parseJSON(response);
        google.charts.load("current", {
          packages: ["corechart", "bar"],
        });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
          let chartDiv = document.getElementById("chart_div");

          let data = "";
          let dataArray = [];
          let dataArrayValue = [];
          dataArrayValue = [];
          dataArrayValue.push("");
          dataArrayValue.push("");
          dataArray.push(dataArrayValue);

          $.each(json, function (i, v) {
            window["monthName" + i] = v.month;
            window["collection" + i] = v.saleAmount;
            dataArrayValue = [];
            dataArrayValue.push(v.month);
            dataArrayValue.push(v.saleAmount);
            dataArray.push(dataArrayValue);
          });
          data = google.visualization.arrayToDataTable(dataArray);
          let options = {
            legend: {
              position: "none",
            },
            colors: ["#7367f0", "#7367f0", "#7367f0"],
            axes: {
              y: {
                all: {
                  format: {
                    pattern: "decimal",
                  },
                },
              },
            },
            series: {
              0: {
                axis: "0",
              },
            },
          };

          function drawMaterialChart() {
            let materialChart = new google.charts.Bar(chartDiv);
            materialChart.draw(data, options);
          }

          function drawClassicChart() {
            let classicChart = new google.visualization.ColumnChart(chartDiv);
            classicChart.draw(data, classicOptions);
          }
          drawMaterialChart();
        }
      },
    });
  }

  $(document).on("change", "#outlet_id_dashboard", function () {
    //change outlet id for dashboard data change
    $("#outlet_form").submit();
  });
});
