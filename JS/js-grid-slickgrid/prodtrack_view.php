    <?php
            
            if (isset($_GET['css'])) {
                require_once "header_css.php";
            } else {
                require_once "header.php";
            }
            
            require_once "buildSourceData.php";
    ?>            
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="slickgrid/slick.grid.css" type="text/css"/>
        <style>
            /*.slick-headerrow-column {
             background: #87ceeb;
             text-overflow: clip;
             -moz-box-sizing: border-box;
             box-sizing: border-box;
             }

             .slick-headerrow-column input {
             margin: 0;
             padding: 0;
             width: 100%;
             height: 100%;
             -moz-box-sizing: border-box;
             box-sizing: border-box;
             }*/
        </style>
        <style>
            .warning-rerun {
            color: black;
            font-weight: bold;
            background-color:#FFCC00;
            }

            .warning-remake {
            color: black;
            font-weight: bold;
            background-color:orange;
            }
            .warning-flowissue{
            color: black;
            font-weight: bold;
            background-color:red;
            }

            .changed {
            background: pink;
            }

            .current-server {
            border: 1px solid black;
            background: orange;
            }

            .slick-headerrow-column {
            background: #87ceeb;
            text-overflow: clip;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            overflow: hidden;
            }

            .slick-headerrow-column input {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            }

        </style>
    </head>
    <body>

            
        <!--<a href="#" onclick="$(this).refreshGrid();">Update grid</a>-->
        

        <div style="position:relative">
            
            <div style="display:none;width:100%; padding-top:5px;padding-bottom:5px" id="refreshInfo">
                <!-- RELOAD DATA INFO BOX -->
                <img src="images/warning.png" width="22px"/>&nbsp;
                Data has NOT been refreshed for more than 5 min. <a href="?">Refresh now</a>
            </div>
            
            <div>
                    <span class='warning-flowissue'>&nbsp;&nbsp;&nbsp;</span> Workflow error
                    <span class='warning-remake'>&nbsp;&nbsp;&nbsp;</span> Remake
                    <span class='warning-rerun'>&nbsp;&nbsp;&nbsp;</span> Rerun
                    <span style='padding-left:50px'><?php echo $txt_totalDBLines ?></span>
            </div>
            
            <div style="width:100%;"> 
                <h1>PRODUCTION</h1>
                <div id="myGrid" style="width:100%;height:400px;"></div>
                
                
            </div>
            <div style="width:100%; margin-top: 20px">
                <h1>RERUNS</h1>
                <div id="reruns" style="width:100%;height:200px;"></div>
            </div>
            <div style="width:100%; margin-top: 5px">
                <h1>REMAKES</h1>
                <div id="remakes" style="width:100%;height:200px;"></div>
            </div>
             <!--
             <div style="width:100%; margin-top: 5px">
                             <h1>PACKED</h1>
                             <div id="packed" style="width:100%;height:200px;"></div>
                         </div>-->
             
            
            <!--<div class="options-panel">
                <h2>Demonstrates:</h2>
                <ul>
                    <li>
                        adding basic keyboard navigation and editing
                    </li>
                    <li>
                        custom editors and validators
                    </li>
                    <li>
                        auto-edit settings
                    </li>
                </ul>
                <h2>Options:</h2>
                <button onclick="grid.setOptions({autoEdit:true})">
                    Auto-edit ON
                </button>
                &nbsp;
                <button onclick="grid.setOptions({autoEdit:false})">
                    Auto-edit OFF
                </button>
            </div>-->
        </div>
        <!--<script src="http://mleibman.github.com/SlickGrid/lib/firebugx.js"></script>-->
        <script src="slickgrid/lib/firebugx.js"></script>
        <script src="slickgrid/lib/jquery-1.7.min.js"></script>
        <script src="slickgrid/lib/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="slickgrid/lib/jquery.jsonp-1.1.0.min.js"></script>
        <script src="slickgrid/lib/jquery.event.drag-2.0.min.js"></script>
        <script src="slickgrid/slick.core.js"></script>
        <script src="slickgrid/slick.dataview.js"></script>
        
        <script src="slickgrid/plugins/slick.cellrangedecorator.js"></script>
        <script src="slickgrid/plugins/slick.cellrangeselector.js"></script>
        <script src="slickgrid/plugins/slick.cellselectionmodel.js"></script>
        <script src="slickgrid/slick.formatters.js"></script>
        <script src="slickgrid/slick.editors.js"></script>
        <script src="slickgrid/slick.grid.js"></script>
        <script src="js/purl.js"></script>

        
        <script >
        			function requiredFieldValidator(value) {
            if(value == null || value == undefined || !value.length) {
                return {
                    valid : false,
                    msg : "This is a required field"
                };
            } else {
                return {
                    valid : true,
                    msg : null
                };
            }
        }
        
        var dataView;
        var grid;
        var data = [];
        
        
        //**Highlight
        
        function reRunFormatter(row, cell, value, columnDef, dataContext) {
            
            //console.log(grid.getColumns().qc_decision.value);
            //console.log(dataContext.qc_decision);
            //console.log(dataContext.order_nb);
            //console.log(dataContext.sheet_nb);
            //console.log(dataContext.planned_end_date);
            var d = new Date();
            var currentDate = new Date();
            var twoDigitMonth=((currentDate.getMonth()+1)>=10)? (currentDate.getMonth()+1) : '0' + (currentDate.getMonth()+1);  
            var twoDigitDate=((currentDate.getDate())>=10)? (currentDate.getDate()) : '0' + (currentDate.getDate());
            var today = twoDigitDate+ "-" + twoDigitMonth+ "-" + currentDate.getFullYear(); 

            //console.log(today);
            
            //CompareDates (dataContext.planned_end_date,today,dataContext);
            
            //console.log (dataContext.qc);
            //if (dataContext.planned_end_date)    
            //+cell+value+columnDef+dataContext);
            //if (dataContext.qc_decision==2 || dataContext.saw_decision==3 || dataContext.saw_decision==4) {
            if (dataContext.checkProductionFlowAccuracy==0){
                return "<span class='warning-flowissue'>" + value + "</span>";
            }else if (dataContext.remake==1) {
              return "<span class='warning-remake'>" + value + "</span>";
            }else if (dataContext.rerun==1) {
              return "<span class='warning-rerun'>" + value + "</span>";
            }else{
              return value;
            }
        }
        
        function CompareDates(str1, str2, dataContext) {
            if (str1 == "")
                return false;
        
            var dt1 = parseInt(str1.substring(0, 2), 10);
        
            var mon1 = parseInt(str1.substring(3, 5), 10);
        
            var yr1 = parseInt(str1.substring(6, 10), 10);
        
            var dt2 = parseInt(str2.substring(0, 2), 10);
        
            var mon2 = parseInt(str2.substring(3, 5), 10);
        
            var yr2 = parseInt(str2.substring(6, 10), 10);
        
            mon1 = mon1 - 1;
        
            mon2 = mon2 - 1;
        
            var date1 = new Date(yr1, mon1, dt1);
        
            var date2 = new Date(yr2, mon2, dt2);
        
            if (date2 > date1) {
        
                //return false;
                //console.log('Late');
                dataContext.checkLateOrders="Late";
        
            } else {
        
                //console.log('Date ok');
                dataContext.checkLateOrders="On time";
        
            }
        
       }
        
        /*************************************
        Define columns for Planning or CSS
        **************************************/
        //**Use purl.js to parse url (jquery url parser)
        var url = $.url();

        if ($.url().param('css')){
            var columns = [{
                id : "checklateorders",
                name : "Timing",
                field : "checklateorders",
                width : 60,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                sortable: true
            },{
                id : "order_nb",
                name : "order_nb",
                field : "order_nb",
                width : 60,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                formatter: reRunFormatter,
                sortable: true
            },{
                id : "order_desc",
                name : "Project",
                field : "order_desc",
                width : 120,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },{
                id : "sheet_desc",
                name : "Sheet desc",
                field : "sheet_desc",
                width : 120,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },{
                id : "sheet_nb",
                name : "Sheet nb",
                field : "sheet_nb",
                width : 50,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },{
                id : "planned_end_date",
                name : "planned_end_date",
                field : "planned_end_date",
                width : 80,
                minWidth : 80,
                cssClass : "cell-title",
                //editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                editor : Slick.Editors.Date,
                sortable: true
            }, {
                id : "qc",
                name : "qc",
                field : "qc",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "saw",
                name : "saw",
                field : "saw",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "fabrication",
                name : "fabrication",
                field : "fabrication",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "packing",
                name : "packing",
                field : "packing",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }];
        }else{
            var columns = [{
                id : "id",
                name : "id",
                field : "id",
                width : 50,
                selectable: false,
                cssClass : "cell-selection",
                validator : requiredFieldValidator,
                cannotTriggerInsert: true
                
            },{
                id : "status",
                name : "Status",
                field : "status",
                width : 30,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                sortable: true
            },{
                id : "checklateorders",
                name : "Timing",
                field : "checklateorders",
                width : 60,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                sortable: true
            },{
                id : "order_nb",
                name : "order_nb",
                field : "order_nb",
                width : 60,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                formatter: reRunFormatter,
                sortable: true
            },{
                id : "order_desc",
                name : "Project",
                field : "order_desc",
                width : 120,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },{
                id : "project_type",
                name : "Complexity",
                field : "project_type",
                width : 40,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                sortable: true
            },{
                id : "sheet_desc",
                name : "Sheet desc",
                field : "sheet_desc",
                width : 120,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },{
                id : "sheet_nb",
                name : "Sheet nb",
                field : "sheet_nb",
                width : 50,
                cssClass : "cell-title",
                editor : Slick.Editors.Text,
                validator : requiredFieldValidator
            },/* {
                id : "planned_start_date",
                name : "planned_start_date",
                field : "planned_start_date",
                width : 80,
                minWidth : 80,
                cssClass : "cell-title",
                editor : Slick.Editors.Date,
                validator : requiredFieldValidator
            },*/ {
                id : "planned_end_date",
                name : "planned_end_date",
                field : "planned_end_date",
                width : 80,
                minWidth : 80,
                cssClass : "cell-title",
                //editor : Slick.Editors.Text,
                validator : requiredFieldValidator,
                editor : Slick.Editors.Date,
                sortable: true
            }, {
                id : "production_start",
                name : "production_start",
                field : "production_start",
                width : 120,
                minWidth : 80,
                cssClass : "cell-title",
                //editor : Slick.Editors.Date,
                validator : requiredFieldValidator,
                sortable: true
            }, {
                id : "production_end",
                name : "production_end",
                field : "production_end",
                width : 120,
                minWidth : 80,
                cssClass : "cell-title",
                //editor : Slick.Editors.Date,
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "last_update",
                name : "last_update",
                field : "last_update",
                width : 120,
                minWidth : 80,
                cssClass : "cell-title",
                //editor : Slick.Editors.Date,
                validator : requiredFieldValidator,
                sortable: true 
            
            }/*, {
                id : "userid",
                name : "userid",
                field : "userid",
                width : 70,
                cssClass : "cell-title",
                validator : requiredFieldValidator
            
            }*/, {
                id : "layup",
                name : "layup",
                field : "layup",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "press",
                name : "press",
                field : "press",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "qc",
                name : "qc",
                field : "qc",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "saw",
                name : "saw",
                field : "saw",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "fabrication",
                name : "fabrication",
                field : "fabrication",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "packing",
                name : "packing",
                field : "packing",
                width : 90,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "failurereason",
                name : "failurereason",
                field : "failurereason",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "qc_decision",
                name : "qc_decision",
                field : "qc_decision",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "sheet_version_number",
                name : "sheet_version_number",
                field : "sheet_version_number",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "checkProductionFlowAccuracy",
                name : "checkProductionFlowAccuracy",
                field : "checkProductionFlowAccuracy",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "remake",
                name : "remake",
                field : "remake",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }, {
                id : "rerun",
                name : "rerun",
                field : "rerun",
                width : 100,
                cssClass : "cell-title",
                validator : requiredFieldValidator,
                sortable: true
            
            }];
        }
        
        var columnFilters = {};
        
        var options = {
            editable : true,
            enableAddRow : true,
            showHeaderRow: true,
            headerRowHeight: 30,
            enableCellNavigation : true,
            asyncEditorLoading : false,
            explicitInitialization: true,
            autoEdit : true
        };
        var options2 = {
            editable : true,
            enableAddRow : true,
            //showHeaderRow: true,
            headerRowHeight: 30,
            enableCellNavigation : true,
            asyncEditorLoading : false,
            //explicitInitialization: true,
            autoEdit : true
        };
        
        function comparer(a, b) {
		  var x = a[sortcol], y = b[sortcol];
		  return (x == y ? 0 : (x > y ? 1 : -1));
		}

        
        function filter(item) {
            for (var columnId in columnFilters) {
              if (columnId !== undefined && columnFilters[columnId] !== "") {
                var c = grid.getColumns()[grid.getColumnIndex(columnId)];
                if (item[c.field] != columnFilters[columnId]) {
                  return false;
                }
              }
            }
            return true;
        }
        
        
        $(function() {
            
            $("#refreshInfo").css("visibility","hidden");
             
            var data = [];
            
            //**TEST
            //data[0] = { id: "1", order_nb: "222", sheet_nb: "1", planned_start_date: "00-00-0000", planned_end_date: "00-00-0000", production_start: "00-00-0000", production_end: "00-00-0000", last_update: "00-00-0000", layup: "", press: "fail", saw: "", qc: "Pass", userid: "2" }; 
            
            <?php echo $data; ?>
				var reruns = [];
            <?php echo $reruns; ?>
				var remakes = [];
            <?php echo $remakes; ?>
				var packed = [];
            
				/*********************
				 FIRST TABLE
				 *********************/
				//**Use dataview instead of just data
				dataView = new Slick.Data.DataView();
				grid = new Slick.Grid("#myGrid", dataView, columns, options);

				//sorting functionality

				grid.onSort.subscribe(function(e, args) {
					sortdir = args.sortAsc ? 1 : -1;
					sortcol = args.sortCol.field;

					if ($.browser.msie && $.browser.version <= 8) {
						// using temporary Object.prototype.toString override
						// more limited and does lexicographic sort only by default, but can be much faster

						var percentCompleteValueFn = function() {
							var val = this["percentComplete"];
							if (val < 10) {
								return "00" + val;
							} else if (val < 100) {
								return "0" + val;
							} else {
								return val;
							}
						};

						// use numeric sort of % and lexicographic for everything else
						dataView.fastSort((sortcol == "percentComplete") ? percentCompleteValueFn : sortcol, args.sortAsc);
					} else {
						// using native sort with comparer
						// preferred method but can be very slow in IE with huge datasets
						dataView.sort(comparer, args.sortAsc);
					}
				});

				grid.onSort.subscribe(function(e, args) {
					sortdir = args.sortAsc ? 1 : -1;
					sortcol = args.sortCol.field;

					dataView.sort(comparer, args.sortAsc);
				});

				dataView.onRowCountChanged.subscribe(function(e, args) {
					grid.updateRowCount();
					grid.render();
				});

				dataView.onRowsChanged.subscribe(function(e, args) {
					grid.invalidateRows(args.rows);
					grid.render();
				});

				$(grid.getHeaderRow()).delegate(":input", "change keyup", function(e) {
					var columnId = $(this).data("columnId");
					if (columnId != null) {
						columnFilters[columnId] = $.trim($(this).val());
						dataView.refresh();
					}
				});

				grid.onHeaderRowCellRendered.subscribe(function(e, args) {
					$(args.node).empty();
					$("<input type='text'>").data("columnId", args.column.id).val(columnFilters[args.column.id]).appendTo(args.node);
				});

				grid.init();

				dataView.beginUpdate();

				dataView.setItems(data);
				dataView.setFilter(filter);
				dataView.endUpdate();

				/********************
				 SECOND TABLE
				 **********************/
				//dataView2 = new Slick.Data.DataView();
				grid2 = new Slick.Grid("#reruns", reruns, columns, options2);
				//grid2 = new Slick.Grid("#reruns", dataView2, columns, options2);

				//dataView2.setItems(reruns);
				//dataView2.setFilter(filter);
				//dataView2.endUpdate();

				/********************
				 THIRD TABLE
				 **********************/
				//dataView3 = new Slick.Data.DataView();
				grid3 = new Slick.Grid("#remakes", remakes, columns, options2);
				//grid3 = new Slick.Grid("#remakes", dataView3, columns, options2);

				//dataView3.setItems(remakes);
				//dataView3.setFilter(filter);
				//dataView3.endUpdate();

				//grid.setSelectionModel(new Slick.CellSelectionModel());

				/********************
				 FOURTH TABLE
				 **********************/
				//grid4 = new Slick.Grid("#packed", packed, columns, options2);

				/*************************************
				 EDIT ACTION
				 **************************************/
				var editedRows = {}
				grid.onAddNewRow.subscribe(function(e, args) {

					//var item = {"num": data.length, "id": "new_" + (Math.round(Math.random() * 10000)), "title": "New task", "duration": "1 day", "percentComplete": 0, "start": "01/01/2009", "finish": "01/01/2009", "effortDriven": false};
					//$.extend(item, args.item);

					var item = args.item;
					//**editedRows can be send to the server PHP for parsing
					editedRows[item.id] = item;
					//alert ("New row");
					//console.log(item);
					sendData(item);
					//alert(item);
					grid.invalidateRow(data.length);
					data.push(item);
					grid.updateRowCount();
					grid.render();
				});

				grid.onCellChange.subscribe(function(e, args) {
					var item = args.item;
					//**editedRows can be send to the server PHP for parsing
					editedRows[item.id] = item;
					alert(item.order_nb);
					//console.log(item);
					updateData(item);
					//alert(item);
					//grid.invalidateRow(data.length);
					//data.push(item);
					//grid.updateRowCount();

					grid.render();
				});

				/*
				grid.onDblClick.subscribe(function(e, args){
				var cell = grid.getCellFromEvent(e);
				//alert (dump(cell))
				console.log(cell.data);
				var row = cell.row;
				//alert (row)
				var column = grid.getColumns()[cell.cell]; // object containing name, field, id, etc
				//alert (column)
				});*/

				//grid.onSelectedRowsChanged.subscribe(function() { console.log(grid.getSelectedRows('order_nb')); });

				//grid.onCellChange = function(currentRow, currentCell, item) {
				/*grid.onCellChange = function (row, col, dataRow) {
				 console.log(row)
				 }   */

				/*grid.onClick.subscribe(function(e, args) {
				 //alert(currentRow + ":" + currentCell + "> key=" + item['order_nb']);
				 //alert("test");
				 console.log(grid.getSelectedRows());
				 console.log(e);
				 console.log(args);
				 //console.log(grid.getData());
				 //console.log(grid.getData());
				 //alert (grid.getData());
				 mydata = grid.SelectedRow;
				 //alert("test" + mydata);
				 //$("input[name='data']").val($.JSON.encode(data));
				 $("input[name='data']").val(mydata);

				 //alert(grid.getCellFromEvent(e).val);
				 //alert(data.value);
				 });*/
				$("form").submit(function() {
					alert("test" + mydata);
					$("input[name='data']").val("mydata");

				});

				function sendData(theData) {
					//generate the parameter for the php script
					//var data = 'page=' + document.location.hash.replace(/^.*#/, '');
					$.ajax({
						url : "save_data.php",
						type : "POST",
						data : theData,
						cache : false,
						success : function(html) {
							//alert ("Success insert");
							//hide the progress bar
							//$('#loading').hide();

							//add the content retrieved from ajax and put it in the #content div
							//$('#content').html(html);

							//display the body with fadeIn transition
							//$('#content').fadeIn('slow');
							grid.render();
							location.reload(true);
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + " " + thrownError + " \r\n\nError in sendData() function in prodtrack_view.php");
						}
					});

				}

				function reloadPage() {
					$("#refreshInfo").css("visibility", "visible");
					$("#refreshInfo").effect("highlight", {}, 3000);
				}

				setInterval(reloadPage, 300000);

				function updateData(theData) {
					$.ajax({
						url : "update_data.php",
						type : "POST",
						data : theData,
						cache : false,
						success : function(html) {
							//alert ("Success update")
							//hide the progress bar
							//$('#loading').hide();

							//add the content retrieved from ajax and put it in the #content div
							//$('#content').html(html);

							//display the body with fadeIn transition
							//$('#content').fadeIn('slow');
							grid.render();
						}
					});

				}

				$.fn.refreshGrid =function(){
				alert ("refresh");

				$.ajax({
				url: "buildSourceData.php",
				type: "POST",
				data: null,
				cache: false,
				success: function (data) {
				//alert (data);
				//console.log(data);
				//hide the progress bar
				//$('#loading').hide();

				//add the content retrieved from ajax and put it in the #content div
				//$('#content').html(html);

				//display the body with fadeIn transition
				//$('#content').fadeIn('slow');
				//alert (grid);

				// data.push(hh);
				// grid.updateRowCount();

				// grid.setData(hh);
				// grid.render();
				var dd=[];
				//dd.push(data);
				//dd=data;

				//data.push(dd);
				//grid.updateRowCount();

				alert ("data="+typeof(data));

				dd[0] = { id: "1", order_nb: "222", sheet_nb: "1", planned_start_date: "00-00-0000", planned_end_date: "00-00-0000", production_start: "00-00-0000", production_end: "00-00-0000", last_update: "00-00-0000", layup: "", press: "fail", saw: "", qc: "Pass", userid: "2" };
				dd[1] = { id: "1", order_nb: "222", sheet_nb: "1", planned_start_date: "00-00-0000", planned_end_date: "00-00-0000", production_start: "00-00-0000", production_end: "00-00-0000", last_update: "00-00-0000", layup: "", press: "fail", saw: "", qc: "Pass", userid: "2" };

				//ddjson=json_encode(dd);
				//ddjson=jQuery.parseJSON(dd);

				alert (dd);
				grid.setData(dd);
				//data2.push(data);
				//alert (data2);
				//grid = new Slick.Grid("#myGrid", data, columns, options);
				grid.render();
				}
				});

				/*$.ajax({
				url: "buildSourceData.php",
				global: false,
				type: "POST",
				data: "{}",
				//data: null,
				contentType: "application/json",
				dataType: "json",
				async: false,
				success: function (json) {

				data = eval('(' + json.d + ')');
				console.log(data);
				if (!data) { alert('no data'); };
				},
				error: function (msg) {
				var errorText = eval('(' + msg.responseText + ')');
				alert('Error : \n--------\n' + errorText.Message);
				}
				});*/

				}//End refreshgrid

				});
</script>
        
<!--
<form method="POST" action="save_data.php">
    <input type="submit" value="Save">
    <input type="text" id = "datatosend" name="data" value="">
</form> -->
    </body>
