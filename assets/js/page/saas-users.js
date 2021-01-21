"use strict";
function queryParams(p){
  return {
    "filter": $('#filter').val(),
    limit:p.limit,
    sort:p.sort,
    order:p.order,
    offset:p.offset,
    search:p.search
  };
}

$('#tool').on('change',function(e){
  $('#users_list').bootstrapTable('refresh');
});