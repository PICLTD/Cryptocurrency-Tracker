<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cryptocurrency Tracker</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cryptocurrency Tracker</h1>
        <input type="text" id="search" class="form-control" placeholder="Search cryptocurrencies..." />

        <div id="loading">
            <div>
                <div class="spinner-border" role="status"></div>
                <div>LOADING...</div>
            </div>
        </div>

        <table class="table table-bordered mt-4" id="cryptoTable">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Symbol</th>
                    <th>Price</th>
                    <th>24h %</th>
                    <th>Volume</th>
                    <th>Market Cap</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div id="pagination" class="text-center mt-3"></div>
    </div>

	<!-- Modal -->
	<div class="modal fade" id="cryptoModal" tabindex="-1" role="dialog" aria-labelledby="cryptoModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="cryptoModalLabel">Cryptocurrency Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-6">
							<strong class="titledesc">Website:</strong>
							<div id="cryptoWebsite" class="text-primary"></div>
						</div>
						<div class="col-6">
							<strong class="titledesc">Launch Date:</strong>
							<div id="cryptoLaunchDate" class="text-muted"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<strong class="titledesc">Content Created On:</strong>
							<div id="cryptoContentCreated" class="text-muted"></div>
						</div>
						<div class="col-6">
							<strong class="titledesc">Platform Type:</strong>
							<div id="cryptoPlatformType" class="text-muted"></div>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col-12">
							<strong class="titledesc">Description:</strong>
							<div id="cryptoDescription" class="text-muted"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<script src="js/script.js"></script>

</body>
</html>
