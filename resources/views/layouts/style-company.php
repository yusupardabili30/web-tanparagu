:root {
    --primary-color: #2c7be5;
    --primary-dark: #1a5bb8;
}

/* Wrapper animasi slide */
#profile-box-wrapper {
    overflow: hidden;
    transition: max-height .35s ease;
    max-height: 0;
}

/* Tombol toggle */
#toggle-profile {
    background-color: var(--primary-color);
    border: none;
    font-weight: 500;
}

#toggle-profile:hover {
    background-color: var(--primary-dark);
}

/* Card Profile Avatar */
.avatar-md .avatar-title {
    width: 68px;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm {
    width: 48px;
    height: 48px;
    object-fit: cover;
}

/* Table Clean */
.table-card table thead tr th {
    font-weight: 600;
    color: #4a4a4a;
    border-bottom: 2px solid #e7e7e7 !important;
}

.table-card table tbody tr:hover td {
    background: #f6faff;
}

/* Profile Info Table */
#profile-box table td {
    padding: .45rem .5rem;
}

/* Clean spacing */
#profile-box .card-body {
    padding: 1.2rem 1.4rem !important;
}

/* Rounded smoother card */
#profile-box, .card {
    border-radius: 12px !important;
}
