<template>
    <!-- Footer -->
    <mdb-footer color="unique-color-dark" class="page-footer font-small pt-0">
        <div style="backgroundColor: #6351ce">
            <mdb-container class="text-left">
                <mdb-row class="py-4 d-flex align-items-center">
                    <mdb-col md="6" lg="5" class="text-center text-md-left mb-4 mb-md-0">
                        <h6 class="mb-0 white-text">Get connected with us on social networks!</h6>
                    </mdb-col>
                    <mdb-col md="6" lg="7" class="text-center text-md-right">
                        <a class="fb-ic ml-0"><i class="fab fa-facebook white-text mr-lg-4"> </i></a>
                        <a class="tw-ic"><i class="fab fa-twitter white-text mr-lg-4"> </i></a>
                        <a class="gplus-ic"><i class="fab fa-google-plus white-text mr-lg-4"> </i></a>
                        <a class="li-ic"><i class="fab fa-linkedin-in white-text mr-lg-4"> </i></a>
                        <a class="ins-ic"><i class="fab fa-instagram white-text mr-lg-4"> </i></a>
                    </mdb-col>
                </mdb-row>
            </mdb-container>
        </div>
        <mdb-container class="mt-5 mb-4 text-center text-md-left">
            <mdb-row class="mt-3">
                <mdb-col md="3" lg="4" xl="3" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Booking</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p>"Our site automates the booking process for users and admins. As the owner, you get to choose
                        who can book. Perhaps it should be the general public, or only invited users, or only invited
                        users with a custom tag, or only a selected set of booking administrators. Your users can
                        easily and securely authenticate themselves!"</p>
                </mdb-col>
                <mdb-col md="2" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Products</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="#!">Monthly</a></p>
                    <p><a href="#!">Weekly</a></p>
                    <p><a href="#!">Bi-weekly</a></p>
                </mdb-col>
                <mdb-col md="3" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Useful links</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="#!">Your Account</a></p>
                    <p><a href="#!">Become an Affiliate</a></p>
                    <p><a href="#!">Book now</a></p>
                    <p><a href="#!">Help</a></p>
                </mdb-col>
                <mdb-col md="4" lg="3" xl="3" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Contact</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><i class="fas fa-home mr-3"></i>Ukraine, Kiev</p>
                    <p><i class="fas fa-envelope mr-3"></i> info@example.com</p>
                    <p><i class="fas fa-phone mr-3"></i> + 01 234 567 88</p>
                    <p><i class="fas fa-print mr-3"></i> + 01 234 567 89</p>
                </mdb-col>
            </mdb-row>
        </mdb-container>
        <div class="footer-copyright text-center py-3">
            <mdb-container fluid>
                &copy; 2019 Copyright: <a href="https://www.MDBootstrap.com">Booking</a>
            </mdb-container>
        </div>
    </mdb-footer>
    <!-- Footer -->
</template>

<script>
    import { mdbFooter, mdbContainer, mdbRow, mdbCol } from 'mdbvue';
    export default {
        name: 'FooterPage',
        components: {
            mdbFooter,
            mdbContainer,
            mdbRow,
            mdbCol
        }
    }
</script>