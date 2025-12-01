@extends('layouts.main-user')

@section('mycontent')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Quiz</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Quiz</a></li>
                        <li class="breadcrumb-item active">Soal</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    //
    <div class="row">

        <div class="col-xxl-8">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">

                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                            </div>
                                        </th>
                                        <th class="sort" data-sort="name" scope="col">Company Name</th>
                                        <th class="sort" data-sort="owner" scope="col">Owner</th>
                                        <th class="sort" data-sort="industry_type" scope="col">Industry Type</th>
                                        <th class="sort" data-sort="star_value" scope="col">Rating</th>
                                        <th class="sort" data-sort="location" scope="col">Location</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                            </div>
                                        </th>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/brands/dribbble.png" alt="" class="avatar-xxs rounded-circle image_src object-fit-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">Nesta Technologies
                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">Tonya Noble</td>
                                        <td class="industry_type">Computer Industry</td>
                                        <td><span class="star_value">4.5</span> <i class="ri-star-fill text-warning align-bottom"></i></td>
                                        <td class="location">Los Angeles, USA</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="Call" data-bs-original-title="Call">
                                                    <a href="javascript:void(0);" class="text-muted d-inline-block">
                                                        <i class="ri-phone-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="Message" data-bs-original-title="Message">
                                                    <a href="javascript:void(0);" class="text-muted d-inline-block">
                                                        <i class="ri-question-answer-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="View" data-bs-original-title="View">
                                                    <a href="javascript:void(0);" class="view-item-btn"><i class="ri-eye-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit">
                                                    <a class="edit-item-btn" href="#showModal" data-bs-toggle="modal"><i class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete">
                                                    <a class="remove-item-btn" data-bs-toggle="modal" href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"><template shadowrootmode="open">
                                            <div class="body" style="--lord-icon-primary-base: #121331; --lord-icon-secondary-base: #08a88a;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" width="500" height="500" preserveAspectRatio="xMidYMid meet" style="width: 100%; height: 100%; transform: translate3d(0px, 0px, 0px); content-visibility: visible;">
                                                    <defs>
                                                        <clipPath id="__lottie_element_260">
                                                            <rect width="500" height="500" x="0" y="0"></rect>
                                                        </clipPath>
                                                    </defs>
                                                    <g clip-path="url(#__lottie_element_260)">
                                                        <g transform="matrix(3.499983072280884,0.010875326581299305,-0.010875326581299305,3.499983072280884,251.46456909179688,250.09307861328125)" opacity="1" style="display: block;">
                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(18,19,48)" stroke-opacity="1" stroke-width="3.5" d=" M20.799999237060547,-37.79100036621094 C36.99599838256836,-21.594999313354492 36.99599838256836,4.664999961853027 20.799999237060547,20.861000061035156 C4.604000091552734,37.05699920654297 -21.656999588012695,37.05699920654297 -37.85300064086914,20.861000061035156 C-54.04899978637695,4.664999961853027 -54.04899978637695,-21.594999313354492 -37.85300064086914,-37.79100036621094 C-21.656999588012695,-53.98699951171875 4.604000091552734,-53.98699951171875 20.799999237060547,-37.79100036621094z"></path>
                                                            </g>
                                                        </g>
                                                        <g transform="matrix(3.499983072280884,0.010875326581299305,-0.010875326581299305,3.499983072280884,251.46456909179688,250.09307861328125)" opacity="1" style="display: block;">
                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(7,168,137)" stroke-opacity="1" stroke-width="3.64" d=" M23.46500015258789,-0.460999995470047 C22.038000106811523,5.136000156402588 19.132999420166016,10.434000015258789 14.753000259399414,14.814000129699707 C1.75600004196167,27.81100082397461 -19.316999435424805,27.81100082397461 -32.31399917602539,14.814000129699707 C-35.04999923706055,12.07800006866455 -37.21099853515625,8.982999801635742 -38.79499816894531,5.682000160217285"></path>
                                                            </g>
                                                        </g>
                                                        <g transform="matrix(3.499983072280884,0.010875326581299305,-0.010875326581299305,3.499983072280884,251.46456909179688,250.09307861328125)" opacity="1" style="display: block;">
                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(18,19,48)" stroke-opacity="1" stroke-width="4.2" d=" M21.486000061035156,21.427000045776367 C21.486000061035156,21.427000045776367 46.641998291015625,46.152000427246094 46.641998291015625,46.152000427246094"></path>
                                                            </g>
                                                        </g>
                                                        <g class="com" style="display: none;">
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg></div>
                                        </template></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ companies We did not find any companies for you search.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0">
                                    <li class="active"><a class="page" href="#" data-i="1" data-page="8">1</a></li>
                                </ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                </div>
                                <form class="tablelist-form" autocomplete="off">
                                    <div class="modal-body">
                                        <input type="hidden" id="id-field">
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                    <div class="position-relative d-inline-block">
                                                        <div class="position-absolute bottom-0 end-0">
                                                            <label for="company-logo-input" class="mb-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Select Image" data-bs-original-title="Select Image">
                                                                <div class="avatar-xs cursor-pointer">
                                                                    <div class="avatar-title bg-light border rounded-circle text-muted">
                                                                        <i class="ri-image-fill"></i>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input class="form-control d-none" value="" id="company-logo-input" type="file" accept="image/png, image/gif, image/jpeg">
                                                        </div>
                                                        <div class="avatar-lg p-1">
                                                            <div class="avatar-title bg-light rounded-circle">
                                                                <img src="assets/images/users/multi-user.jpg" id="companylogo-img" class="avatar-md rounded-circle object-fit-cover">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5 class="fs-13 mt-3">Company Logo</h5>
                                                </div>
                                                <div>
                                                    <label for="companyname-field" class="form-label">Name</label>
                                                    <input type="text" id="companyname-field" class="form-control" placeholder="Enter company name" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="owner-field" class="form-label">Owner Name</label>
                                                    <input type="text" id="owner-field" class="form-control" placeholder="Enter owner name" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="industry_type-field" class="form-label">Industry Type</label>
                                                    <select class="form-select" id="industry_type-field">
                                                        <option value="">Select industry type</option>
                                                        <option value="Computer Industry">Computer Industry</option>
                                                        <option value="Chemical Industries">Chemical Industries</option>
                                                        <option value="Health Services">Health Services</option>
                                                        <option value="Telecommunications Services">Telecommunications Services</option>
                                                        <option value="Textiles: Clothing, Footwear">Textiles: Clothing, Footwear</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="star_value-field" class="form-label">Rating</label>
                                                    <input type="text" id="star_value-field" class="form-control" placeholder="Enter rating" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="location-field" class="form-label">Location</label>
                                                    <input type="text" id="location-field" class="form-control" placeholder="Enter location" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <label for="employee-field" class="form-label">Employee</label>
                                                    <input type="text" id="employee-field" class="form-control" placeholder="Enter employee" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="website-field" class="form-label">Website</label>
                                                    <input type="text" id="website-field" class="form-control" placeholder="Enter website" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="contact_email-field" class="form-label">Contact Email</label>
                                                    <input type="text" id="contact_email-field" class="form-control" placeholder="Enter contact email" required="">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="since-field" class="form-label">Since</label>
                                                    <input type="text" id="since-field" class="form-control" placeholder="Enter since" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" id="add-btn">Add Company</button>
                                            <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end add modal-->

                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" id="deleteRecord-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"><template shadowrootmode="open">
                                            <div class="body" style="--lord-icon-primary-base: #405189; --lord-icon-secondary-base: #f06548;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" width="500" height="500" preserveAspectRatio="xMidYMid meet" style="width: 100%; height: 100%; transform: translate3d(0px, 0px, 0px); content-visibility: visible;">
                                                    <defs>
                                                        <clipPath id="__lottie_element_131">
                                                            <rect width="500" height="500" x="0" y="0"></rect>
                                                        </clipPath>
                                                        <clipPath id="__lottie_element_134">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <clipPath id="__lottie_element_138">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <clipPath id="__lottie_element_142">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <clipPath id="__lottie_element_146">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <clipPath id="__lottie_element_152">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <g id="__lottie_element_155">
                                                            <g transform="matrix(4.5,0,0,4.5,270.24798583984375,271.24951171875)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path fill="rgb(255,0,0)" fill-opacity="1" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <filter id="__lottie_element_165" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_155_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_165)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_155"></use>
                                                            </g>
                                                        </mask>
                                                        <g id="__lottie_element_178">
                                                            <g clip-path="url(#__lottie_element_179)" transform="matrix(1,0,0,1,0,0)" opacity="1" style="display: block;">
                                                                <g transform="matrix(1,0,0,1,235,261)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                    </g>
                                                                </g>
                                                                <g transform="matrix(1,0,0,1,162,279.5730895996094)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <clipPath id="__lottie_element_179">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <filter id="__lottie_element_188" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_178_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_188)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_178"></use>
                                                            </g>
                                                        </mask>
                                                        <clipPath id="__lottie_element_196">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <g id="__lottie_element_199">
                                                            <g transform="matrix(4.544222831726074,0,0,4.4477362632751465,270.4293212890625,273.0283203125)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path fill="rgb(255,0,0)" fill-opacity="1" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <filter id="__lottie_element_205" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_199_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_205)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_199"></use>
                                                            </g>
                                                        </mask>
                                                        <clipPath id="__lottie_element_207">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <g id="__lottie_element_210">
                                                            <g clip-path="url(#__lottie_element_211)" transform="matrix(1,0,0,1,0,0)" opacity="1" style="display: block;">
                                                                <g transform="matrix(1,0,0,1,235,252.53944396972656)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M19.586999893188477,-212.0330047607422 C19.586999893188477,-212.0330047607422 -33.41299819946289,-126.03299713134766 -33.41299819946289,-126.03299713134766 C-33.41299819946289,-126.03299713134766 6.883999824523926,-77.10299682617188 6.883999824523926,-77.10299682617188 C6.883999824523926,-77.10299682617188 60.88399887084961,-91.10299682617188 60.88399887084961,-91.10299682617188 C60.88399887084961,-91.10299682617188 85.58699798583984,-141.0330047607422 85.58699798583984,-141.0330047607422 C85.58699798583984,-141.0330047607422 19.586999893188477,-212.0330047607422 19.586999893188477,-212.0330047607422z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M19.586999893188477,-212.0330047607422 C19.586999893188477,-212.0330047607422 -33.41299819946289,-126.03299713134766 -33.41299819946289,-126.03299713134766 C-33.41299819946289,-126.03299713134766 6.883999824523926,-77.10299682617188 6.883999824523926,-77.10299682617188 C6.883999824523926,-77.10299682617188 60.88399887084961,-91.10299682617188 60.88399887084961,-91.10299682617188 C60.88399887084961,-91.10299682617188 85.58699798583984,-141.0330047607422 85.58699798583984,-141.0330047607422 C85.58699798583984,-141.0330047607422 19.586999893188477,-212.0330047607422 19.586999893188477,-212.0330047607422z"></path>
                                                                    </g>
                                                                </g>
                                                                <g transform="matrix(1,0,0,1,235,251.52362060546875)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                    </g>
                                                                </g>
                                                                <g transform="matrix(1,0,0,1,162,244.0213623046875)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <clipPath id="__lottie_element_211">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <g id="__lottie_element_217">
                                                            <g clip-path="url(#__lottie_element_218)" transform="matrix(1,0,0,1,0,0)" opacity="1" style="display: block;">
                                                                <g transform="matrix(1,0,0,1,235,251.52362060546875)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                    </g>
                                                                </g>
                                                                <g transform="matrix(1,0,0,1,162,244.0213623046875)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <clipPath id="__lottie_element_218">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <filter id="__lottie_element_227" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_217_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_227)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_217"></use>
                                                            </g>
                                                        </mask>
                                                        <filter id="__lottie_element_228" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_210_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_228)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_210"></use>
                                                            </g>
                                                        </mask>
                                                        <g id="__lottie_element_247">
                                                            <g transform="matrix(4.5,0,0,4.5,270.2480163574219,271.24951171875)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path fill="rgb(255,0,0)" fill-opacity="1" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <clipPath id="__lottie_element_297">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <filter id="__lottie_element_300" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_247_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_300)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_247"></use>
                                                            </g>
                                                        </mask>
                                                        <g id="__lottie_element_327">
                                                            <g clip-path="url(#__lottie_element_328)" style="display: block;" transform="matrix(1,0,0,1,0,0)" opacity="1">
                                                                <g transform="matrix(1,0,0,1,235,261)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                    </g>
                                                                </g>
                                                                <g transform="matrix(1,0,0,1,162,262)" opacity="1" style="display: block;">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        <path fill="rgb(255,0,0)" fill-opacity="1" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <clipPath id="__lottie_element_328">
                                                            <path d="M0,0 L500,0 L500,500 L0,500z"></path>
                                                        </clipPath>
                                                        <filter id="__lottie_element_331" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%">
                                                            <feComponentTransfer in="SourceGraphic">
                                                                <feFuncA type="table" tableValues="1.0 0.0"></feFuncA>
                                                            </feComponentTransfer>
                                                        </filter>
                                                        <mask id="__lottie_element_327_2" mask-type="alpha">
                                                            <g filter="url(#__lottie_element_331)">
                                                                <rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect>
                                                                <use xlink:href="#__lottie_element_327"></use>
                                                            </g>
                                                        </mask>
                                                    </defs>
                                                    <g clip-path="url(#__lottie_element_131)">
                                                        <g clip-path="url(#__lottie_element_134)" transform="matrix(1,0,0,1,0,0)" opacity="0" style="display: block;">
                                                            <g mask="url(#__lottie_element_155_2)" style="display: block;">
                                                                <g clip-path="url(#__lottie_element_152)" transform="matrix(1,0,0,1,0,0)" opacity="1">
                                                                    <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19,-241 C19,-241 -34,-155 -34,-155 C-34,-155 0,-130 0,-130 C0,-130 54,-144 54,-144 C54,-144 85,-170 85,-170 C85,-170 19,-241 19,-241z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M69,-208 C69,-208 0,-162 0,-162 C0,-162 50,-97 50,-97 C50,-97 104,-111 104,-111 C104,-111 119,-177 119,-177 C119,-177 69,-208 69,-208z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,162,441)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g mask="url(#__lottie_element_178_2)" style="display: block;">
                                                                        <g transform="matrix(1,0,0,1,235,261)" opacity="1">
                                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19.586999893188477,-212.0330047607422 C19.586999893188477,-212.0330047607422 -33.41299819946289,-126.03299713134766 -33.41299819946289,-126.03299713134766 C-33.41299819946289,-126.03299713134766 6.883999824523926,-77.10299682617188 6.883999824523926,-77.10299682617188 C6.883999824523926,-77.10299682617188 60.88399887084961,-91.10299682617188 60.88399887084961,-91.10299682617188 C60.88399887084961,-91.10299682617188 85.58699798583984,-141.0330047607422 85.58699798583984,-141.0330047607422 C85.58699798583984,-141.0330047607422 19.586999893188477,-212.0330047607422 19.586999893188477,-212.0330047607422z"></path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,235,261)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,162,279.5730895996094)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g transform="matrix(4.5,0,0,4.5,270.24798583984375,271.24951171875)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-17.020000457763672,-17.8439998626709 C-17.020000457763672,-17.8439998626709 -17.020000457763672,16.802000045776367 -17.020000457763672,16.802000045776367 M-3.9820001125335693,-17.8439998626709 C-3.9820001125335693,-17.8439998626709 -3.9820001125335693,16.802000045776367 -3.9820001125335693,16.802000045776367 M8.998000144958496,-17.8439998626709 C8.998000144958496,-17.8439998626709 8.998000144958496,16.802000045776367 8.998000144958496,16.802000045776367"></path>
                                                                </g>
                                                            </g>
                                                            <g transform="matrix(-1.3905764818191528,-4.279754161834717,4.279754161834717,-1.3905764818191528,77.97709655761719,-138.9887237548828)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,-3.9690001010894775,-34.03099822998047)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-9.904000282287598,-1.3910000324249268 C-9.22599983215332,-6.251999855041504 -5.046999931335449,-10 0,-10 C0,-10 0,-10 0,-10 C4.709000110626221,-10 8.661999702453613,-6.73799991607666 9.720999717712402,-2.3529999256134033"></path>
                                                                    </g>
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-35.08000183105469,-34.125 C-35.08000183105469,-34.125 26.878999710083008,-33.95500183105469 26.878999710083008,-33.95500183105469"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g clip-path="url(#__lottie_element_138)" transform="matrix(1,0,0,1,0,0)" opacity="0" style="display: block;">
                                                            <g mask="url(#__lottie_element_199_2)" style="display: block;">
                                                                <g clip-path="url(#__lottie_element_196)" transform="matrix(1,0,0,1,0,0)" opacity="1">
                                                                    <g mask="url(#__lottie_element_210_2)" style="display: block;">
                                                                        <g clip-path="url(#__lottie_element_207)" transform="matrix(1,0,0,1,0,0)" opacity="1">
                                                                            <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: none;">
                                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19,-241 C19,-241 -34,-155 -34,-155 C-34,-155 0,-130 0,-130 C0,-130 54,-144 54,-144 C54,-144 85,-170 85,-170 C85,-170 19,-241 19,-241z"></path>
                                                                                </g>
                                                                            </g>
                                                                            <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: none;">
                                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M69,-208 C69,-208 0,-162 0,-162 C0,-162 50,-97 50,-97 C50,-97 104,-111 104,-111 C104,-111 119,-177 119,-177 C119,-177 69,-208 69,-208z"></path>
                                                                                </g>
                                                                            </g>
                                                                            <g transform="matrix(1,0,0,1,162,189.03067016601562)" opacity="1" style="display: block;">
                                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    <g mask="url(#__lottie_element_217_2)" style="display: block;">
                                                                        <g transform="matrix(1,0,0,1,235,252.53944396972656)" opacity="1">
                                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19.586999893188477,-212.0330047607422 C19.586999893188477,-212.0330047607422 -33.41299819946289,-126.03299713134766 -33.41299819946289,-126.03299713134766 C-33.41299819946289,-126.03299713134766 6.883999824523926,-77.10299682617188 6.883999824523926,-77.10299682617188 C6.883999824523926,-77.10299682617188 60.88399887084961,-91.10299682617188 60.88399887084961,-91.10299682617188 C60.88399887084961,-91.10299682617188 85.58699798583984,-141.0330047607422 85.58699798583984,-141.0330047607422 C85.58699798583984,-141.0330047607422 19.586999893188477,-212.0330047607422 19.586999893188477,-212.0330047607422z"></path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,235,251.52362060546875)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,162,244.0213623046875)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g transform="matrix(4.544222831726074,0,0,4.4477362632751465,270.4293212890625,273.0283203125)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-17.020000457763672,-17.8439998626709 C-17.020000457763672,-17.8439998626709 -17.020000457763672,16.802000045776367 -17.020000457763672,16.802000045776367 M-3.9820001125335693,-17.8439998626709 C-3.9820001125335693,-17.8439998626709 -3.9820001125335693,16.802000045776367 -3.9820001125335693,16.802000045776367 M8.998000144958496,-17.8439998626709 C8.998000144958496,-17.8439998626709 8.998000144958496,16.802000045776367 8.998000144958496,16.802000045776367"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g clip-path="url(#__lottie_element_142)" transform="matrix(1,0,0,1,0,0)" opacity="0" style="display: block;">
                                                            <g mask="url(#__lottie_element_247_2)" style="display: none;">
                                                                <g clip-path="url(#__lottie_element_297)" transform="matrix(1,0,0,1,0,0)" opacity="1">
                                                                    <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19,-241 C19,-241 -34,-155 -34,-155 C-34,-155 0,-130 0,-130 C0,-130 54,-144 54,-144 C54,-144 85,-170 85,-170 C85,-170 19,-241 19,-241z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,235,440)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M69,-208 C69,-208 0,-162 0,-162 C0,-162 50,-97 50,-97 C50,-97 104,-111 104,-111 C104,-111 119,-177 119,-177 C119,-177 69,-208 69,-208z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,162,441)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g mask="url(#__lottie_element_327_2)" style="display: block;">
                                                                        <g transform="matrix(1,0,0,1,235,261)" opacity="1">
                                                                            <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M19.586999893188477,-212.0330047607422 C19.586999893188477,-212.0330047607422 -33.41299819946289,-126.03299713134766 -33.41299819946289,-126.03299713134766 C-33.41299819946289,-126.03299713134766 6.883999824523926,-77.10299682617188 6.883999824523926,-77.10299682617188 C6.883999824523926,-77.10299682617188 60.88399887084961,-91.10299682617188 60.88399887084961,-91.10299682617188 C60.88399887084961,-91.10299682617188 85.58699798583984,-141.0330047607422 85.58699798583984,-141.0330047607422 C85.58699798583984,-141.0330047607422 19.586999893188477,-212.0330047607422 19.586999893188477,-212.0330047607422z"></path>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,235,261)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M66.69000244140625,-187.8489990234375 C66.69000244140625,-187.8489990234375 27.91699981689453,-150.6649932861328 27.91699981689453,-150.6649932861328 C27.91699981689453,-150.6649932861328 50.20899963378906,-91.96199798583984 50.20899963378906,-91.96199798583984 C50.20899963378906,-91.96199798583984 116.80400085449219,-106.34500122070312 116.80400085449219,-106.34500122070312 C116.80400085449219,-106.34500122070312 115.43099975585938,-140.85899353027344 115.43099975585938,-140.85899353027344 C115.43099975585938,-140.85899353027344 66.69000244140625,-187.8489990234375 66.69000244140625,-187.8489990234375z"></path>
                                                                        </g>
                                                                    </g>
                                                                    <g transform="matrix(1,0,0,1,162,262)" opacity="1" style="display: block;">
                                                                        <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="12.04" d=" M100.11799621582031,-119.88200378417969 C100.11799621582031,-119.88200378417969 78.38700103759766,-199.91200256347656 78.38700103759766,-199.91200256347656 C78.38700103759766,-199.91200256347656 0.8820000290870667,-173.1179962158203 0.8820000290870667,-173.1179962158203 C0.8820000290870667,-173.1179962158203 -2.9749999046325684,-117.46600341796875 -2.9749999046325684,-117.46600341796875 C-2.9749999046325684,-117.46600341796875 54.85599899291992,-82.3010025024414 54.85599899291992,-82.3010025024414 C54.85599899291992,-82.3010025024414 100.11799621582031,-119.88200378417969 100.11799621582031,-119.88200378417969z"></path>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                            <g transform="matrix(4.5,0,0,4.5,270.2480163574219,271.24951171875)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-17.020000457763672,-17.8439998626709 C-17.020000457763672,-17.8439998626709 -17.020000457763672,16.802000045776367 -17.020000457763672,16.802000045776367 M-3.9820001125335693,-17.8439998626709 C-3.9820001125335693,-17.8439998626709 -3.9820001125335693,16.802000045776367 -3.9820001125335693,16.802000045776367 M8.998000144958496,-17.8439998626709 C8.998000144958496,-17.8439998626709 8.998000144958496,16.802000045776367 8.998000144958496,16.802000045776367"></path>
                                                                </g>
                                                            </g>
                                                            <g transform="matrix(2.3912546634674072,-3.812073230743408,3.812073230743408,2.3912546634674072,329.8857727050781,93.877197265625)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,-3.9690001010894775,-34.03099822998047)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-9.904000282287598,-1.3910000324249268 C-9.22599983215332,-6.251999855041504 -5.046999931335449,-10 0,-10 C0,-10 0,-10 0,-10 C4.709000110626221,-10 8.661999702453613,-6.73799991607666 9.720999717712402,-2.3529999256134033"></path>
                                                                    </g>
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-35.08000183105469,-34.125 C-35.08000183105469,-34.125 26.878999710083008,-33.95500183105469 26.878999710083008,-33.95500183105469"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g clip-path="url(#__lottie_element_146)" transform="matrix(1,0,0,1,0,0)" opacity="1" style="display: block;">
                                                            <g transform="matrix(4.433560848236084,-0.7704156637191772,0.7704156637191772,4.433560848236084,243.75450134277344,270.35205078125)" opacity="1" style="display: block;">
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M21.97800064086914,-33.762001037597656 C21.975793838500977,-33.871826171875 21.881916046142578,-33.96055221557617 21.76850128173828,-33.959999084472656 C21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 21.76850128173828,-33.959999084472656 C21.65508460998535,-33.95944595336914 18.874412536621094,-33.967586517333984 15.563030242919922,-33.97816467285156 C15.563030242919922,-33.97816467285156 -23.781030654907227,-34.10383605957031 -23.781030654907227,-34.10383605957031 C-27.0924129486084,-34.11441421508789 -29.841941833496094,-34.12210464477539 -29.91699981689453,-34.121002197265625 C-29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 -29.91699981689453,-34.121002197265625 C-29.99205780029297,-34.119895935058594 -30.054567337036133,-34.078224182128906 -30.056499481201172,-34.02799987792969 C-30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 -30.056499481201172,-34.02799987792969 C-30.05843162536621,-33.97777557373047 -30.061738967895508,-31.248401641845703 -30.06388282775879,-27.937002182006836 C-30.06388282775879,-27.937002182006836 -30.100116729736328,28.035001754760742 -30.100116729736328,28.035001754760742 C-30.10226058959961,31.34640121459961 -27.4153995513916,34.03499984741211 -24.104000091552734,34.03499984741211 C-24.104000091552734,34.03499984741211 16.118999481201172,34.03499984741211 16.118999481201172,34.03499984741211 C19.43039894104004,34.03499984741211 22.113550186157227,31.346405029296875 22.106840133666992,28.035011291503906 C22.106840133666992,28.035011291503906 21.994159698486328,-27.563011169433594 21.994159698486328,-27.563011169433594 C21.987449645996094,-30.874404907226562 21.980207443237305,-33.65217208862305 21.97800064086914,-33.762001037597656 C21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656 21.97800064086914,-33.762001037597656z"></path>
                                                                </g>
                                                                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-17.020000457763672,-17.8439998626709 C-17.020000457763672,-17.8439998626709 -17.020000457763672,16.802000045776367 -17.020000457763672,16.802000045776367 M-3.9820001125335693,-17.8439998626709 C-3.9820001125335693,-17.8439998626709 -3.9820001125335693,16.802000045776367 -3.9820001125335693,16.802000045776367 M8.998000144958496,-17.8439998626709 C8.998000144958496,-17.8439998626709 8.998000144958496,16.802000045776367 8.998000144958496,16.802000045776367"></path>
                                                                </g>
                                                                <g opacity="1" transform="matrix(0.9620532989501953,-0.2728615999221802,0.2728615999221802,0.9620532989501953,7.981511116027832,-10.017486572265625)">
                                                                    <g opacity="1" transform="matrix(1,0,0,1,-3.9690001010894775,-34.03099822998047)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(64,81,136)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-9.904000282287598,-1.3910000324249268 C-9.22599983215332,-6.251999855041504 -5.046999931335449,-10 0,-10 C0,-10 0,-10 0,-10 C4.709000110626221,-10 8.661999702453613,-6.73799991607666 9.720999717712402,-2.3529999256134033"></path>
                                                                    </g>
                                                                    <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(239,100,71)" stroke-opacity="1" stroke-width="2.8000000000000003" d=" M-35.08000183105469,-34.125 C-35.08000183105469,-34.125 26.878999710083008,-33.95500183105469 26.878999710083008,-33.95500183105469"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                        <g class="com" style="display: none;">
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                            <g>
                                                                <path></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg></div>
                                        </template></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to delete a company ?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Deleting your company will remove all of your information from our database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none" data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1 align-middle"></i> Close
                                            </button>
                                            <button class="btn btn-danger" id="delete-record">Yes, Delete It!!</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end delete modal -->

                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-4">
            <div class="card" id="company-view-detail">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <div class="avatar-md">
                            <div class="avatar-title bg-light rounded-circle">
                                <img src="assets/images/brands/mail_chimp.png" alt="" class="avatar-sm rounded-circle object-fit-cover">
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-3 mb-1">Syntyce Solution</h5>
                    <p class="text-muted">Michael Morris</p>

                    <ul class="list-inline mb-0">
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);" class="avatar-title bg-success-subtle text-success fs-15 rounded">
                                <i class="ri-global-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);" class="avatar-title bg-danger-subtle text-danger fs-15 rounded">
                                <i class="ri-mail-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item avatar-xs">
                            <a href="javascript:void(0);" class="avatar-title bg-warning-subtle text-warning fs-15 rounded">
                                <i class="ri-question-answer-line"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-3">Information</h6>
                    <p class="text-muted mb-4">A company incurs fixed and variable costs such as the purchase of raw materials, salaries and overhead, as explained by AccountingTools, Inc. Business owners have the discretion to determine the actions.</p>
                    <div class="table-responsive table-card">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-semibold" scope="row">Industry Type</td>
                                    <td>Chemical Industries</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Location</td>
                                    <td>Damascus, Syria</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Employee</td>
                                    <td>10-50</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Rating</td>
                                    <td>4.0 <i class="ri-star-fill text-warning align-bottom"></i></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Website</td>
                                    <td>
                                        <a href="javascript:void(0);" class="link-primary text-decoration-underline">www.syntycesolution.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Contact Email</td>
                                    <td>info@syntycesolution.com</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold" scope="row">Since</td>
                                    <td>1995</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    //
    <!-- end page title -->



</div>
@endsection

@section('sipproja-js')
<script>

</script>
@endsection