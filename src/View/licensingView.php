<?php

class licensingView extends licensingModel {
    public function showAllLicenses(): array
    {
        return $this->getAllLicenses();
    }
}