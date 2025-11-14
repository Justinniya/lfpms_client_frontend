<style>
    /* Custom Table Styling */
    .custom-table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Table Header */
    .custom-table thead {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }

    .custom-table thead th {
      padding: 12px;
      text-align: center;
    }

    /* Table Rows */
    .custom-table tbody tr {
      transition: all 0.3s ease-in-out;
    }

    .custom-table tbody tr:hover {
      background: rgba(0, 123, 255, 0.1);
    }

    /* Table Cells */
    .custom-table td {
      padding: 12px;
      text-align: center;
      vertical-align: middle;
    }

    /* Product Image Styling */
    .product-img {
      max-width: 250px;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Responsive Table */
    @media (max-width: 768px) {
      .product-img {
        max-width: 50px;
      }

      .custom-table td {
        font-size: 14px;
      }
    }

    /* Space out the DataTable components */
    .dataTables_wrapper {
      padding: 15px 0;
    }

    /* Space between Show Entries and Search */
    .dataTables_length {
      margin-bottom: 15px;
      margin-left: 10px;
    }


    .dataTables_filter {
      margin-bottom: 15px;
      margin-left: 350px;
    }

    /* Add spacing below the table */
    .dataTables_info {
      margin-top: 15px;
    }

    /* Add spacing above pagination */
    .dataTables_paginate {
      margin-top: 15px;
    }

    /* Fix horizontal scrollbar issue */
    .dataTables_wrapper .row {
      margin-bottom: 10px;
    }
  </style>