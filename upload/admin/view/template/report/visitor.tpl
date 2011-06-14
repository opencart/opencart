<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">

    </div>
  </div>
</div>




<script type="text/javascript"><!--
var myService;
var dataTable;
var sourceTable;
var sourceChart;
var sourceColumnChart;
var scope = 'https://www.google.com/analytics/feeds';

/**
 * Initialize all the objects from the Google AJAX Apis once they
 *     have been loaded and are ready to use.
 */
function init() {
  myService =
      new google.gdata.analytics.AnalyticsService('gaExportAPI_gViz_v1.0');
  sourceTable =
      new google.visualization.Table(document.getElementById('sourceTableDiv'));
  sourceChart =
      new google.visualization.PieChart(document.getElementById('sourceChartDiv'));
  sourceColumnChart =
      new google.visualization.ColumnChart(document.getElementById('sourceColumnChartDiv'));

  getStatus();
}
/**
 * Utility function to setup the login/logout functionality.
 */
function getStatus() {
  var status = document.getElementById('status');
  var buttonLogin = document.getElementById('buttonLogin');
  var inputP = document.getElementById('inputP');
  buttonLogin.style.visibility = 'visible';

  if (!google.accounts.user.checkLogin(scope)) {
    buttonLogin.value = 'Login';
    buttonLogin.onclick = login;
    status.innerHTML = 'You are logged out, login to continue';
    inputP.style.visibility = 'hidden';
  } else {
    buttonLogin.value = 'Logout';
    buttonLogin.onclick = logout;
    status.innerHTML = 'You are logged in';
    inputP.style.visibility = 'visible';
  }
}

/**
 * AuthSub Authentication to allow users to grant this script
 *     access to their GA data.
 */
function login() {
  google.accounts.user.login(scope);
  getStatus();
}

/**
 * AuthSub Authentication to allow users to remove this script
 *     access to their GA data.
 */
function logout() {
  google.accounts.user.logout();
  getStatus();
}

/**
 * Request data from GA Export API
 */
function getDataFeed() {
  var myFeedUri = scope + '/data' +
    '?start-date=2008-10-01' +
    '&end-date=2008-10-31' +
    '&dimensions=ga:pageTitle,ga:pagePath' +
    '&metrics=ga:pageviews' +
    '&sort=-ga:pageviews' +
    '&max-results=10' +
    '&ids=ga:' + document.getElementById('inputProfileId').value;

  myService.getDataFeed(myFeedUri, handleMyDataFeed, handleError);
}

/**
 * Handle and display any error that occurs from the API request.
 * @param {Object} e The error object returned by the Analytics API.
 */
function handleError(e) {
  var msg = e.cause ? e.cause.statusText : e.message;
  msg = 'ERROR: ' + msg;
  alert(msg);
}

/**
 * Handle all the data returned by GA Export API.
 * Delete existing GViz dataTable before creating a new one.
 * @param {Object} myResultsFeedRoot the feed object
 *     retuned by the data feed.
 */
function handleMyDataFeed(myResultsFeedRoot) {
  dataTable = new google.visualization.DataTable();
  fillDataTable(dataTable, myResultsFeedRoot);
  sourceTable.draw(dataTable);

  // remove the URI column to only graph 1 dimension
  dataTable.removeColumn(0);
  sourceChart.draw(dataTable, {width: 500, height: 400, is3D: true});
  sourceColumnChart.draw(dataTable, {width: 500, height: 300, is3D: true, title: 'Company Performance'});
}

/**
 * Put the feed result into a GViz Data Table.
 * @param {Object} dataTable the GViz dataTable object to put data into.
 * @param {Object} myResultsFeedRoot the feed returned by the GA Export API.
 * @return {Objcet} GViz DataTable object.
 */
function fillDataTable(dataTable, myResultsFeedRoot) {
  var entries = myResultsFeedRoot.feed.getEntries();

  dataTable.addColumn('string', 'Page Title');
  dataTable.addColumn('string', 'Page Uri Path');
  dataTable.addColumn('number', 'Pageviews');

  if (entries.length == 0) {
    dataTable.addRows(1);
    dataTable.setCell(0, 0, 'No Data');
    dataTable.setCell(0, 1, 0);
  } else {
    dataTable.addRows(entries.length);
    for (var idx = 0; idx < entries.length; idx++) {
      var entry = entries[idx];
      var title = entry.getValueOf('ga:pageTitle');
      var keyword = entry.getValueOf('ga:pagePath');
      var visits = entry.getValueOf('ga:pageviews');
      dataTable.setCell(idx, 0, title);
      dataTable.setCell(idx, 1, keyword);
      dataTable.setCell(idx, 2, visits);
    }
  }
}

// Load the Google Visualization API client Libraries
google.load('visualization', '1', {packages: ['piechart', 'table', 'columnchart']});

// Load the Google data JavaScript client library
google.load('gdata', '1.x');

google.setOnLoadCallback(init);

/*
function handleAccountFeed(result) {
  // An array of analytics feed entries.
  var entries = result.feed.getEntries();

  // Create an HTML Table using an array of elements.
  var outputTable = ['<table><tr>',
                     '<th>Account Name</th>',
                     '<th>Profile Name</th>',
                     '<th>Profile ID</th>',
                     '<th>Table Id</th></tr>'];

  // Iterate through the feed entries and add the data as table rows.
  for (var i = 0, entry; entry = entries[i]; ++i) {

    // Add a row in the HTML Table array for each value.
    var row = [
      entry.getPropertyValue('ga:AccountName'),
      entry.getTitle().getText(),
      entry.getPropertyValue('ga:ProfileId'),
      entry.getTableId().getValue()
    ].join('</td><td>');
    outputTable.push('<tr><td>', row, '</td></tr>');
  }
  outputTable.push('</table>');

  // Insert the table into the UI.
  document.getElementById('outputDiv').innerHTML =
      outputTable.join('');
}


function getDataFeed() {
var myFeedUri = 'https://www.google.com/analytics/feeds/data' +
    '?start-date=2009-04-01' +
    '&end-date=2009-04-30' +
    '&dimensions=ga:pageTitle,ga:pagePath' +
    '&metrics=ga:pageviews' +
    '&sort=-ga:pageviews' +
    '&max-results=10' +
    '&ids=' + document.getElementById('tableId').value;
  
  myService.getDataFeed(myFeedUri, handleDataFeed, handleError);
}

function handleDataFeed(result) {
 
 // An array of Analytics feed entries.
 var entries = result.feed.getEntries();
 
 // Create an HTML Table using an array of elements.
 var outputTable = ['<table><tr>',
                    '<th>Page Title</th>',
                    '<th>Page Path</th>',
                    '<th>Pageviews</th></tr>'];

  // Iterate through the feed entries and add the data as table rows.
  for (var i = 0, entry; entry = entries[i]; ++i) {

     // Add a row in the HTML Table array.
     var row = [
       entry.getValueOf('ga:pageTitle'),
       entry.getValueOf('ga:pagePath'),
       entry.getValueOf('ga:pageviews')
     ].join('</td><td>');
     outputTable.push('<tr><td>', row, '</td></tr>');
   }
   outputTable.push('</table>');

  // Insert the table into the UI.
  document.getElementById('outputDiv').innerHTML =
      outputTable.join('');
}


function handleError(e) {
    var error = 'There was an error!\n';
    if (e.cause) {
      error += e.cause.status;
    } else {
      error.message;
    }
    alert(error);
}  
*/
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>