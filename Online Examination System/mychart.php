<? 
  include( 'chartlogix.inc.php' ); 
    
  $pie = new PieChart(); 
    
  $pie->setTitle( "ChartLogix Pie Chart" ); 
    
  $pie->addData( 'Bananas', 420, 'FFCC00' ); 
  $pie->addData( 'Apples', 400, '99FF00' ); 
  $pie->addData( 'Strawberries', 210, 'FF6666' ); 
  $pie->addData( 'Grapes', 350, '009900' ); 
  $pie->addData( 'Plums', 100, '9900CC' ); 
  $pie->addData( 'Others', 190, 'AAAAAA' ); 
    
  $pie->drawPNG( 500, 400 ); 
?>