var apiBase = 'http://parts.dev';

var getStockedParts = function(parts, callback) {
    // Build a query from them
    var queryParts = parts.map(function(elem){ return 'pn[]='+encodeURIComponent(elem); });
    var queryString = queryParts.join('&');
    
    // Run the query
    httpRequest = new XMLHttpRequest();
    httpRequest.onreadystatechange = function(){
       if (httpRequest.readyState === XMLHttpRequest.DONE && httpRequest.status === 200) {
            var data = JSON.parse(httpRequest.responseText);
            callback(data);
       }
    };
    httpRequest.open('GET', apiBase+'/api/lookup?'+queryString, true);
    httpRequest.send(null);
}

var getIconForPart = function(partId)
{
    var a = document.createElement("a");
    a.href = apiBase+'/?q='+encodeURIComponent(partId);
    a.textContent = 'AVAIL. LOCALLY';
    a.target = "_blank";
    a.style.display = "block"
    return a;
}

var siteConfigs = [
    {
        name: 'mouser',
        searchTableElem: 'table.SearchResultsTable',
        pnElem: 'tr .mfrDiv > a:first-child',
        pnPartParentElem: 'tr',
        insertLocationElem: 'td.td-select'
    },
    {
        name: 'digikey',
        searchTableElem: 'table#productTable',
        pnElem: '.tr-mfgPartNumber a:first-child',
        pnPartParentElem: 'tr',
        insertLocationElem: '.tr-compareParts'
    }
];

siteConfigs.forEach(function(site) {
    if (document.querySelectorAll(site.searchTableElem).length > 0) {
        var resultsDom = document.querySelectorAll(site.searchTableElem+' '+site.pnElem);
        var results = Array.prototype.slice.call(resultsDom);
        var resultsParts = results.map(function(elem){ return elem.textContent.trim(); });
        
        getStockedParts(resultsParts, function(stocked){
            results.forEach(function(elem) {
                if (stocked.indexOf(elem.textContent.trim()) !== -1) {
                    var checkboxTd = elem.closest(site.pnPartParentElem).querySelector(site.insertLocationElem);
                    checkboxTd.appendChild(getIconForPart(elem.textContent.trim()));
                }
            });
        });
    }
});
