   </div><!--/.sb-content-->
  </div><!--/.sb-main-->
</div><!--/.sb-wrap-->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
// ====== Chart.js plugin: show "No data yet" ======
const NoDataPlugin = {
  id: 'noData',
  afterDraw(chart, args, opts){
    const hasData = chart.data?.datasets?.some(ds => (ds.data||[]).length);
    if (hasData) return;
    const {ctx, chartArea} = chart;
    if (!chartArea) return;
    const {left, right, top, bottom} = chartArea;
    ctx.save();
    ctx.fillStyle = opts?.color || '#9aa6b2';
    ctx.font = '600 14px system-ui, -apple-system, "Segoe UI", Roboto, Arial';
    ctx.textAlign = 'center';
    ctx.fillText('No data yet', (left+right)/2, (top+bottom)/2);
    ctx.restore();
  }
};
Chart.register(NoDataPlugin);

// ====== Init empty charts (placeholder) ======
if (document.getElementById('areaChart')) {
  new Chart(document.getElementById('areaChart'), {
    type:'line',
    data:{labels:[], datasets:[]},
    options:{
      plugins:{legend:{display:false}, noData:{color:'#9aa6b2'}},
      scales:{x:{display:false}, y:{display:false}}
    }
  });
}

if (document.getElementById('donutChart')) {
  new Chart(document.getElementById('donutChart'), {
    type:'doughnut',
    data:{labels:[], datasets:[]},
    options:{
      plugins:{legend:{display:false}, noData:{color:'#9aa6b2'}},
      cutout:'65%'
    }
  });
}
</script>

</body>
</html>
