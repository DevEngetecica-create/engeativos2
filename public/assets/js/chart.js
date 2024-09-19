
$(function () {
  /* ChartJS
   * -------
   * Data and config for chartjs
   */
  'use strict';

  //inicio Grafico de Ativos Externos

  var jsonAtivosExternos = $('#ativosExternos').val()
  const arrayAtivosExternos = JSON.parse(jsonAtivosExternos);

 
  //console.log(datasFormatadas); // A matriz agora incluirá o novo elemento no final


  var data = {
    labels: arrayAtivosExternos.map(row => row.mes),
    datasets: [{
      label: 'Total de Ferramentas',
      borderColor: '#6781d7',
      backgroundColor: '#6781d7',
      data: arrayAtivosExternos.map(row => row.quantidade_acumulada_criados)
    },
    {
      label: 'Ferramentas descartadas',
      borderColor: '#f44336',
      backgroundColor: '#f44336',
      data: arrayAtivosExternos.map(row => row.quantidade_anterior)
    }
    ]

  };

  var options = {
    plugins: {
      datalabels: {
        value: '1',
        display: true,
        color: '#333', // Cor do texto
        anchor: 'top',
        align: 'top',
        
      }
    },
    scales: {
      y: {
        max: 4000, // Define o valor máximo do eixo Y
        beginAtZero: true, // Isso garante que o eixo comece em zero
      }
    },
    
  };

  // Get context with jQuery - using jQuery's .get() method.
  if ($("#bar-chart-grouped").length) {
    var barChartCanvas = $("#bar-chart-grouped").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChartd = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });
  }
  //Fim Grafico de Ativos Externos

  
   // Início gráfico Total Veiculos e Máquinas
   
const numeroTotalVeiculos = parseFloat($('#totalVeiculos').val()); // número inteiro
 
  const formatoMoeda = numeroTotalVeiculos.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });


  //formatar numero inteiro para moeda Real -> Máquinas
  const numeroTotalMaquinas = parseFloat($('#totalMaquinas').val()); // número inteiro
  const formatoMoedaMaquinas = numeroTotalMaquinas.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });

  //const valorFormatTotalMaquinas = document.getElementById('valorFormatTotalMaquinas');
  //valorFormatTotalMaquinas.innerHTML = formatoMoedaMaquinas;


  var totalGeralVeiculos = numeroTotalVeiculos + numeroTotalMaquinas;
  const formatTotalGeralVeiculos = totalGeralVeiculos.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  });

  const totalGeralV = document.getElementById('totalGeralVeiculos');

  totalGeralV.innerHTML = formatTotalGeralVeiculos;
  
  

   new Chart(document.getElementById("chartTotalVeiculos"), {
    type: 'pie',
    data: {
      labels: ["Máquinas","Veículos"],
      datasets: [{
        label: "Total",
        
        backgroundColor: ['#0d6efd', '#ff7707'],
        borderColor: ['#fff','#fff'],
        
        data: [numeroTotalMaquinas,numeroTotalVeiculos]
        
      }]
    },
    options: {
      title: {
        display: true,
        text: 'Total (R$) de Veiculos por tipo'
      },
      plugins: {
          datalabels: {
    
            display: true,
            color: '#fff', // Cor do texto
            anchor: 'top',
            align: 'top',
    
            formatter: function (value, context) {
              return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value) // Exibir o valor do dado diretamente na barra
            }
          },
          
        },
    }
});
   // FIM gráfico Total Veiculos e Máquinas
   
  //inicio Grafico dos veículos

  //formatar numero inteiro para moeda Real -> Veiculos
 

  console.log(formatTotalGeralVeiculos);



  var jsonString = $('#valorTotalVeiculos').val();
  const arrayDeObjetos = JSON.parse(jsonString);

console.log($('#valorTotalVeiculos').val());

  const valorTotalVeiculos = arrayDeObjetos.map(objeto => objeto.sumtotalVeiculos);

  const valoresFormatados = valorTotalVeiculos.map(valor => {
    const numero = parseFloat(valor); // Converter a string em um número
    if (!isNaN(numero)) {
      return numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      });
    } else {
      return 'Valor inválido';
    }
  });

  //ajustar a escala do eixo Y
  var arrayDadosVeiculos = valorTotalVeiculos;
  var limiteDadosVeiculos = 10000000;

  arrayDadosVeiculos.push(limiteDadosVeiculos);

  console.log(jsonString);

  // Dados do gráfico
  const dataVeiculos = {
    labels: ["Caminhões", "Carros", "Máquinas"],
    datasets: [{
      label: ["Veículos"],
      backgroundColor: [
        '#0d6efd',
        '#ff7707',
        '#198754'
      ],
      data: arrayDadosVeiculos
    },

    ]
  };

  // Opções do gráfico
  const optionsVeiculos = {
        scales: {
          y: {
            beginAtZero: true,
    
          }
        },
        plugins: {
          datalabels: {
    
            display: true,
            color: '#333', // Cor do texto
            anchor: 'top',
            align: 'top',
    
            formatter: function (value, context) {
              return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value) // Exibir o valor do dado diretamente na barra
            }
          },
          
        },
  };

  // Criando o gráfico de barras
  const ctx = document.getElementById('myChart').getContext('2d');
  const myChart = new Chart(ctx, {
    type: 'bar', // Tipo de gráfico (bar para gráfico de barras)
    data: dataVeiculos, // Dados do gráfico
    options: optionsVeiculos // Opções do gráfico
  });


  // fim grafico dos veículos


  // Início gráfico quanatidade de ferramentas por obra

  var jsonQtdeFerramentoaObra = $('#qtdeObras').val()
  const arrayQtdeFerramentoaObra = JSON.parse(jsonQtdeFerramentoaObra);

  const nomeFantasia = arrayQtdeFerramentoaObra.map(row => row.nome_fantasia)
  const qtdeAtivosObras = arrayQtdeFerramentoaObra.map(row => row.qtdeAtivosObras)

  console.log(nomeFantasia)

  var dataAtivosObra = {
    labels: nomeFantasia,
    datasets: [{
      label: 'Qtde de Ativos por Obra',
      borderColor: '#ff7707',
      backgroundColor: '#ff7707',
      data: qtdeAtivosObras
    },    
    ]

  };

  var optionsAtivosObra = {
    plugins: {
      datalabels: {
        value: '1',
        display: true,
        color: '#333', // Cor do texto
        anchor: 'top',
        align: 'top',
       
      }
    },
    scales: {
      y: {
        title: {
          display: true,
          text: 'Qtde'
        },
        min: 0,
        max: 6000,

      },
      x: {
        title: {
          display: true,
          text: 'Mês/ ano'
        },
        grid: {
          display: false, // Oculta as grades do eixo X
        },
      }
    },
  };

  // Get context with jQuery - using jQuery's .get() method.
  if ($("#ferramentasObras").length) {
    var barAtivosObra = $("#ferramentasObras").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barAtivosObra, {
      type: 'bar',
      data: dataAtivosObra,
      options: optionsAtivosObra
    });
  }

  // FIM gráfico quanatidade de ferramentas por obra


   // Início gráfico quanatidade de ferramentas por obra

   var jsonQtdeFerramentasCaliObra = $('#qtdeObras').val()
   const arrayQtdeFerramentasCaliObra = JSON.parse(jsonQtdeFerramentasCaliObra);
 
   const nomeFantasiaCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.nome_fantasia)
   const qtdeAtivosObrasCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.qtdeAtivosObras)
   const qtdeCalibradosObrasCalibrados = arrayQtdeFerramentasCaliObra.map(row => row.qtdeCalibrados)

 
 
   var dataFerramentasCaliObra = {
     labels: nomeFantasiaCalibrados,
     datasets: [
      {
       label: 'Qtde de Ativos por Obra',
       borderColor: '#0d6efd',
       backgroundColor: '#0d6efd',
       data: qtdeAtivosObrasCalibrados
     },
     {
      label: 'Equip. Calibrados',
      borderColor: '#ff7707',
      backgroundColor: '#ff7707',
      data: qtdeCalibradosObrasCalibrados
    }    
     ]
 
   };
 
   var optionsFerramentasCaliObra = {
     plugins: {
       datalabels: {
         value: '1',
         display: true,
         color: '#333', // Cor do texto
         anchor: 'top',
         align: 'top',
         value: qtdeAtivosObras,
         formatter: function (value, context) {
           return value; // Exibir o valor do dado diretamente na barra
         }
       }
     },
     scales: {
       y: {
         title: {
           display: true,
           text: 'Qtde'
         },
         min: 0,        
 
       },
       x: {
         title: {
           display: true,
           text: 'Mês/ ano'
         },
         grid: {
           display: false, // Oculta as grades do eixo X
         },
       }
     },
   };
 
   // Get context with jQuery - using jQuery's .get() method.
   if ($("#ferramentasCalibradasObras").length) {
     var barFerramentasCaliObra = $("#ferramentasCalibradasObras").get(0).getContext("2d");
     // This will get the first returned node in the jQuery collection.
     var barFerramentasCaliObra = new Chart(barFerramentasCaliObra, {
       type: 'bar',
       data: dataFerramentasCaliObra,
       options: optionsFerramentasCaliObra
     });
   }
 
   // FIM gráfico quanatidade de ferramentas calibradas por obra

  var dataDark = {
    labels: ["2013", "2014", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [10, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: false
    }]
  };
  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
      label: 'Dataset 1',
      data: [12, 19, 3, 5, 2, 3],
      borderColor: [
        '#587ce4'
      ],
      borderWidth: 2,
      fill: false
    },
    {
      label: 'Dataset 2',
      data: [5, 23, 7, 12, 42, 23],
      borderColor: [
        '#ede190'
      ],
      borderWidth: 2,
      fill: false
    },
    {
      label: 'Dataset 3',
      data: [15, 10, 21, 32, 12, 33],
      borderColor: [
        '#f44252'
      ],
      borderWidth: 2,
      fill: false
    }
    ]
  };



  var optionsDark = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        },
        gridLines: {
          color: '#322f2f',
          zeroLineColor: '#322f2f'
        }
      }],
      xAxes: [{
        ticks: {
          beginAtZero: true
        },
        gridLines: {
          color: '#322f2f',
        }
      }],
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };

  var doughnutPieData = {
    datasets: [{
      data: [30, 40, 30],
      backgroundColor: [
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'Pink',
      'Blue',
      'Yellow',
    ]
  };
  var doughnutPieOptions = {
    responsive: true,
    animation: {
      animateScale: true,
      animateRotate: true
    }
  };
  var areaData = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: true, // 3: no fill
    }]
  };

  var areaDataDark = {
    labels: ["2013", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1,
      fill: true, // 3: no fill
    }]
  };

  var areaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    }
  }

  var areaOptionsDark = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true,
          max: '100'
        },
        gridLines: {
          color: '#322f2f',
          zeroLineColor: '#322f2f'
        }
      }],
      xAxes: [{
        ticks: {
          beginAtZero: true
        },
        gridLines: {
          color: '#322f2f',
        }
      }],
    },
    plugins: {
      filler: {
        propagate: true
      }
    }
  }

  var multiAreaData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: 'Facebook',
      data: [8, 11, 13, 15, 12, 13, 16, 15, 13, 19, 11, 14],
      borderColor: ['rgba(255, 99, 132, 0.5)'],
      backgroundColor: ['rgba(255, 99, 132, 0.5)'],
      borderWidth: 1,
      fill: true
    },
    {
      label: 'Twitter',
      data: [7, 17, 12, 16, 14, 18, 16, 12, 15, 11, 13, 9],
      borderColor: ['rgba(54, 162, 235, 0.5)'],
      backgroundColor: ['rgba(54, 162, 235, 0.5)'],
      borderWidth: 1,
      fill: true
    },
    {
      label: 'Linkedin',
      data: [6, 14, 16, 20, 12, 18, 15, 12, 17, 19, 15, 11],
      borderColor: ['rgba(255, 206, 86, 0.5)'],
      backgroundColor: ['rgba(255, 206, 86, 0.5)'],
      borderWidth: 1,
      fill: true
    }
    ]
  };

  var multiAreaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    },
    elements: {
      point: {
        radius: 0
      }
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  var scatterChartData = {
    datasets: [{
      label: 'First Dataset',
      data: [{
        x: -10,
        y: 0
      },
      {
        x: 0,
        y: 3
      },
      {
        x: -25,
        y: 5
      },
      {
        x: 40,
        y: 5
      }
      ],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)'
      ],
      borderWidth: 1
    },
    {
      label: 'Second Dataset',
      data: [{
        x: 10,
        y: 5
      },
      {
        x: 20,
        y: -30
      },
      {
        x: -25,
        y: 15
      },
      {
        x: -10,
        y: 5
      }
      ],
      backgroundColor: [
        'rgba(54, 162, 235, 0.2)',
      ],
      borderColor: [
        'rgba(54, 162, 235, 1)',
      ],
      borderWidth: 1
    }
    ]
  }

  var scatterChartDataDark = {
    datasets: [{
      label: 'First Dataset',
      data: [{
        x: -10,
        y: 0
      },
      {
        x: 0,
        y: 3
      },
      {
        x: -25,
        y: 5
      },
      {
        x: 40,
        y: 5
      }
      ],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)'
      ],
      borderWidth: 1
    },
    {
      label: 'Second Dataset',
      data: [{
        x: 10,
        y: 5
      },
      {
        x: 20,
        y: -30
      },
      {
        x: -25,
        y: 15
      },
      {
        x: -10,
        y: 5
      }
      ],
      backgroundColor: [
        'rgba(54, 162, 235, 0.2)',
      ],
      borderColor: [
        'rgba(54, 162, 235, 1)',
      ],
      borderWidth: 1
    }
    ]
  }

  var scatterChartOptions = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom'
      }]
    }
  }

  var scatterChartOptionsDark = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom',
        gridLines: {
          color: '#322f2f',
          zeroLineColor: '#322f2f'
        }
      }],
      yAxes: [{
        gridLines: {
          color: '#322f2f',
          zeroLineColor: '#322f2f'
        }
      }]
    }
  }


  if ($("#barChart").length) {
    var barChartCanvasDark = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChartDark = new Chart(barChartCanvasDark, {
      type: 'bar',
      data: dataDark,
      options: optionsDark
    });
  }
/* 
  if ($("#lineChart").length) {
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: data,
     // options: options
    });
  } */

  if ($("#lineChartDark").length) {
    var lineChartCanvasDark = $("#lineChartDark").get(0).getContext("2d");
    var lineChartDark = new Chart(lineChartCanvasDark, {
      type: 'line',
      data: dataDark,
      options: optionsDark
    });
  }

 /*  if ($("#linechart-multi").length) {
    var multiLineCanvas = $("#linechart-multi").get(0).getContext("2d");
    var lineChart = new Chart(multiLineCanvas, {
      type: 'line',
      data: multiLineData,
      options: options
    });
  }
 */
  if ($("#areachart-multi").length) {
    var multiAreaCanvas = $("#areachart-multi").get(0).getContext("2d");
    var multiAreaChart = new Chart(multiAreaCanvas, {
      type: 'line',
      data: multiAreaData,
      options: multiAreaOptions
    });
  }

  if ($("#doughnutChart").length) {
    var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }

  if ($("#areaChartDark").length) {
    var areaChartCanvas = $("#areaChartDark").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaDataDark,
      options: areaOptionsDark
    });
  }

  if ($("#scatterChart").length) {
    var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartData,
      options: scatterChartOptions
    });
  }

  if ($("#scatterChartDark").length) {
    var scatterChartCanvas = $("#scatterChartDark").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartDataDark,
      options: scatterChartOptionsDark
    });
  }

  if ($("#browserTrafficChart").length) {
    var doughnutChartCanvas = $("#browserTrafficChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: browserTrafficData,
      options: doughnutPieOptions
    });
  }
});