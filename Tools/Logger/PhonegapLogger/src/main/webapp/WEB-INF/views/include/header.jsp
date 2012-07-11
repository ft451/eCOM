<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<html>
	<head>
		<!-- STYLE -->
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
		<style>
		body {
			padding-top: 60px;
			/* 60px to make the container go all the way to the bottom of the topbar */
		}
		</style>

<!-- JAVASCRIPT -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>
	</head>
<body>
	<div class="navbar navbar-fixed-top fill">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="./">E-Shopping Logger</a>
				<c:if test="${user.getId() != null}">
					<div class="pull-right">
						<a class="btn" href="./logout">Logout</a>
					</div>
				</c:if>
			</div>
		</div>
	</div>