<%@ include file="/WEB-INF/views/include/header.jsp" %>
<div class="container">
	<div class="row">
		<div class="well">
			<form class="form-horizontal" action="./index" method="POST">
				<fieldset>
					<div
						class="control-group <c:if test="${loginError == true}">error</c:if>">
						<label class="control-label" for="inputlogin">Login</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="inputlogin"
								name="inputlogin" value="${oldLogin}"> <span
								class="help-inline">${loginErrorMessage}</span>
						</div>
					</div>
					<div
						class="control-group <c:if test="${passwordError == true}">error</c:if>">
						<label class="control-label" for="inputpassword">Haslo</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="inputpassword"
								name="inputpassword"> <span class="help-inline">${passwordErrorMessage}</span>
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" class="btn btn-primary" value="Zaloguj sie!" />
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<%@ include file="/WEB-INF/views/include/footer.jsp" %>