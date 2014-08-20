<h3 class="hiddenlink"><a href="?p=modpacks">Making a modpack?</a></h3>
<?
  $job = json_decode(file_get_contents("http://jenkins.dries007.net/job/D3Core/api/json?tree=url,description,name,displayName,lastStableBuild[url,artifacts[*],timestamp]"), true);
  $lastStableBuild = $job["lastStableBuild"];
  $files = array();
  foreach ($lastStableBuild["artifacts"] as $file)
  {
    if (!contains($file["fileName"], "jar")) continue;
    
    if (contains($file["fileName"], "dev")) $files["dev"] = $file;
    else if (contains($file["fileName"], "src")) $files["src"] = $file;
    else $files["normal"] = $file;
  }
  $versions = @json_decode(file_get_contents("$lastStableBuild[url]artifact/versions.json"), true);
?>
<div class="panel panel-success">
  <div class="panel-heading"><h2 class="panel-title-custom hiddenlink"><a href="?p=modlist&mod=<? echo $job["name"] ?>"><? echo $job["displayName"] ?></a></h2></div>
  <div class="panel-body">
    <p><? echo $job["description"] ?></p>
    <h3>Last successful build</h3>
    <p>
      <? echo date("Y-m-d H:i", $lastStableBuild["timestamp"] / 1000) ?><br>
      Version: <? echo $versions["version"] ?><br>
      Minecraft: <? echo $versions["mcversion"] ?><br>
      Forge: <? echo $versions["apiversion"] ?><br>
      By using this mod you agree to its licence (included in the download).
    </p>
    <div class="btn-group">
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
        Download <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["normal"]["relativePath"]?>"><? echo $files["normal"]["fileName"] ?></a></li>
        <li class="divider"></li>
        <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["dev"]["relativePath"]?>"><? echo $files["dev"]["fileName"] ?></a></li>
        <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["src"]["relativePath"]?>"><? echo $files["src"]["fileName"] ?></a></li>
      </ul>
    </div>
  </div>
</div>
<hr>
<?
  $jobs = json_decode(file_get_contents("http://jenkins.dries007.net/view/DoubleDoorDevelopment/api/json"), true)["jobs"];
  $amountOfJobs = count($jobs);
  $centerd = isset($_GET["mod"]) || $amountOfJobs == 1;
  $i = 0;
  foreach ($jobs as $job)
  {
    if ($job["name"] === "D3Core") continue;
    $newRow = $i % 2 == 0;
    if (isset($_GET["mod"]) && $job["name"] !== $_GET["mod"]) continue;
    if ($centerd || $newRow) echo "<div class=\"row\">";
    $job = json_decode(file_get_contents("$job[url]api/json?tree=url,description,name,displayName,lastStableBuild[url,artifacts[*],timestamp]"), true);
    $lastStableBuild = $job["lastStableBuild"];
    $files = array();
    foreach ($lastStableBuild["artifacts"] as $file)
    {
      if (!contains($file["fileName"], "jar")) continue;
      
      if (contains($file["fileName"], "dev")) $files["dev"] = $file;
      else if (contains($file["fileName"], "src")) $files["src"] = $file;
      else $files["normal"] = $file;
    }
    $versions = @json_decode(file_get_contents("$lastStableBuild[url]artifact/versions.json"), true);
    ?>
    <div class="col-md-6<? if ($centerd) echo " col-md-offset-3"?>">
      <div class="panel panel-info">
        <div class="panel-heading"><h2 class="panel-title-custom hiddenlink"><a href="?p=modlist&mod=<? echo $job["name"] ?>"><? echo $job["displayName"] ?></a></h2></div>
        <div class="panel-body">
          <p><? echo $job["description"] ?></p>
          <h3>Last successful build</h3>
          <p>
            <? echo date("Y-m-d H:i", $lastStableBuild["timestamp"] / 1000) ?><br>
            Version: <? echo $versions["version"] ?><br>
            Minecraft: <? echo $versions["mcversion"] ?><br>
            Forge: <? echo $versions["apiversion"] ?><br>
            By using this mod you agree to its licence (included in the download).
          </p>
          <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
              Download <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["normal"]["relativePath"]?>"><? echo $files["normal"]["fileName"] ?></a></li>
              <li class="divider"></li>
              <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["dev"]["relativePath"]?>"><? echo $files["dev"]["fileName"] ?></a></li>
              <li><a href="<? echo $lastStableBuild["url"] . "artifact/" . $files["src"]["relativePath"]?>"><? echo $files["src"]["fileName"] ?></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?
    if ($centerd || !$newRow) echo "</div>";
    $i++;
  }
  if (!$centerd && $amountOfJobs % 2 != 0) echo "</div>";
?>
