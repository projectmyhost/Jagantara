<?php

class HomeController extends Controller {
    private Banner $bannerModel;
    private Category $categoryModel;
    private Report $reportModel;
    private Activity $activityModel;
    private Region $regionModel;

    public function __construct() {
        parent::__construct();
        $this->bannerModel = new Banner();
        $this->categoryModel = new Category();
        $this->reportModel = new Report();
        $this->activityModel = new Activity();
        $this->regionModel = new Region();
    }

    public function index(): void {

        $banners = $this->bannerModel->getActive();

        $categories = $this->categoryModel->getActive();

        $recentReports = $this->reportModel->getRecent(8);

        $upcomingActivities = $this->activityModel->getRecent(3);

        $regions = $this->regionModel->getParents();

        $stats = [
            'total_reports' => $this->reportModel->count(['is_public' => 1]),
            'resolved_reports' => $this->reportModel->count(['status' => STATUS_COMPLETED, 'is_public' => 1]),
            'total_activities' => $this->activityModel->count(['status' => 'published']),
            'total_users' => (new User())->count(['status' => 'active']),
        ];

        $this->view('home.index', [
            'pageTitle'          => APP_NAME . ' - ' . APP_TAGLINE,
            'banners'            => $banners,
            'categories'         => $categories,
            'recentReports'      => $recentReports,
            'upcomingActivities' => $upcomingActivities,
            'regions'            => $regions,
            'stats'              => $stats,
        ]);
    }
}
