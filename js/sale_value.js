
// NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
// IT'S ALL JUST JUNK FOR DEMO
// ++++++++++++++++++++++++++++++++++++++++++

const sales_chart_options = {
  series: [{
      name: "Digital Goods",
      data: [28, 48, 40, 19, 86, 27, 90],
    },
    {
      name: "Electronics",
      data: [65, 59, 80, 81, 56, 55, 40],
    },
  ],
  chart: {
    height: 300,
    type: "area",
    toolbar: {
      show: false,
    },
  },
  legend: {
    show: false,
  },
  colors: ["#0d6efd", "#20c997"],
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: "smooth",
  },
  xaxis: {
    type: "datetime",
    categories: [
      "2023-01-01",
      "2023-02-01",
      "2023-03-01",
      "2023-04-01",
      "2023-05-01",
      "2023-06-01",
      "2023-07-01",
    ],
  },
  tooltip: {
    x: {
      format: "MMMM yyyy",
    },
  },
};

const sales_chart = new ApexCharts(
  document.querySelector("#revenue-chart"),
  sales_chart_options
);
sales_chart.render();

// <!-- jsvectormap -->