<%@ include file="/WEB-INF/views/include/header.jsp" %>
<div class="container">
	<ul class="pager">
		<li><c:if test="${poprzednia}">
				<a href="./logger?strona=${numerPoprzedniej}">Poprzednia</a>
			</c:if></li>
		<li><c:if test="${nastepna}">
				<a href="./logger?strona=${numerNastepnej}">Nastepna</a>
			</c:if></li>
	</ul>
	<table class="table table-striped table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th>Data</th>
				<th>Operacja</th>
				<th>URL</th>
				<th>IP</th>
				<th>MAC</th>
				<th>UUID</th>
				<th>IMEI</th>
			</tr>
		</thead>
		<c:forEach var="wynik" items="${wyniki}" varStatus="iter">
			<tr>
				<td>${wynik.getId()}</td>
				<td>${wynik.getData()}</td>
				<td>${wynik.getOperacja()}</td>
				<td>${wynik.getUrl()}</td>
				<td>${wynik.getIp()}</td>
				<td>${wynik.getMac()}</td>
				<td>${wynik.getUuid()}</td>
				<td>${wynik.getImei()}</td>
			</tr>
		</c:forEach>
	</table>
</div>
<%@ include file="/WEB-INF/views/include/footer.jsp" %>